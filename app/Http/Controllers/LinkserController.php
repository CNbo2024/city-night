<?php

namespace App\Http\Controllers;

use App\Models\BookingTmp;
use App\Models\Bookings;
use App\Models\User;
use App\Models\PropertyDates;
use App\Models\PropertyPrice;
use App\Models\Properties;
use Illuminate\Http\Request;
use App\Http\Helpers\Common;

class LinkserController extends Controller
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new Common;
    }

    public function payment(Request $request)
    {

        $this->hmac_sha256 = 'sha256';
        $this->secret_ley = '8f249d5ae2b244c1973a8f8569cc0260790be9d7d9f74971b32d3d9b341d13c6bbe5a1527e834aabbbfac8a8194d4f08cae53cfd4dc246bba3d81eafdc62601f750c814169524349819db6d24cce24c7c034b349262c499c8304420a1b9951a38cf550c170ca4e9d9f7e0d7fd35995225d276b1d2b834c7fb2c4a82330933f6c';

        $expiry_date = explode('-', $request->card_expiration_date);
        $expiry_date = $expiry_date[1] . '-' . $expiry_date[0];

        $params['access_key'] = 'd9037799fc8832e7978e6fff23d6fa6c';
        $params['profile_id'] = '21E9B4C6-8A25-4716-BED4-F655276C0860';
        $params['transaction_uuid'] = uniqid();

        $params['signed_field_names'] = 'access_key,profile_id,transaction_uuid,signed_field_names,unsigned_field_names,signed_date_time,locale,transaction_type,reference_number,amount,currency,payment_method,bill_to_forename,bill_to_surname,bill_to_email,bill_to_phone,bill_to_address_line1,bill_to_address_city,bill_to_address_state,bill_to_address_country,bill_to_address_postal_code';

        $params['request'] = json_encode($request->all());

        $params['unsigned_field_names'] = 'card_type,card_number,card_expiry_date,card_cvn';
        $params['signed_date_time'] = gmdate("Y-m-d\TH:i:s\Z");
        $params['locale'] = 'en';
        $params['transaction_type'] = 'authorization';
        $params['reference_number'] = uniqid();
        $params['amount'] = $request->amount;
        $params['currency'] = 'BOB';
        $params['payment_method'] = 'card';
        $params['bill_to_forename'] = auth()->user()->first_name;
        $params['bill_to_surname'] = auth()->user()->last_name;
        $params['bill_to_email'] = auth()->user()->email;
        $params['bill_to_phone'] = auth()->user()->phone;
        $params['bill_to_address_line1'] = auth()->user()->address ?? 'Barrio nueva via';
        $params['bill_to_address_city'] = auth()->user()->city ?? 'Santa Cruz de la Sierra';
        $params['bill_to_address_state'] = auth()->user()->state;
        $params['bill_to_address_country'] = 'BO';
        $params['bill_to_address_postal_code'] = '94043';

        echo "<form action=\"https://testsecureacceptance.cybersource.com/silent/pay\" method=\"post\">\n";

        foreach($params as $name => $value) {
            echo "<input type=\"hidden\" id=\"" . $name . "\" name=\"" . $name . "\" value=\"" . $value . "\"/>\n";
        }

        echo "<input type=\"hidden\" id=\"card_type\" name=\"card_type\" value=\"" . $request->card_type . "\"/>\n";
        echo "<input type=\"hidden\" id=\"card_number\" name=\"card_number\" value=\"" . $request->card_number . "\"/>\n";
        echo "<input type=\"hidden\" id=\"card_expiry_date\" name=\"card_expiry_date\" value=\"" . $expiry_date . "\"/>\n";
        echo "<input type=\"hidden\" id=\"card_cvn\" name=\"card_cvn\" value=\"" . $request->card_cvn . "\"/>\n";

        echo "<input type=\"hidden\" id=\"signature\" name=\"signature\" value=\"" . $this->sign($params) . "\"/>\n";
        echo "</form>\n";

        echo "<script src=\"https://code.jquery.com/jquery-3.7.1.js\"></script>\n";

        echo "<script>\n";
        echo "$(document).ready(function() {\n";
        echo "$('form').submit();\n";
        echo "});\n";
        echo "</script>\n";
    }

    public function sign ($params)
    {
      return $this->signData($this->buildDataToSign($params), $this->secret_ley);
    }

    public function signData($data, $secretKey)
    {
        return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    }

    public function buildDataToSign($params)
    {
        $signedFieldNames = explode(",",$params["signed_field_names"]);

        foreach ($signedFieldNames as $field) {
           $dataToSign[] = $field . "=" . $params[$field];
        }

        return $this->commaSeparate($dataToSign);
    }

    public function commaSeparate ($dataToSign)
    {
        return implode(",",$dataToSign);
    }

    public function receipt(Request $request)
    {
        $tmp = BookingTmp::first();

        \Auth::login(User::find($tmp->user_id));

        if ($request->message == 'Request was processed successfully.') {
            $booking = new Bookings();

            foreach ($tmp->getAttributes() as $key => $value) {
                if ($key != 'id' || $key != 'status') {
                    $booking->$key = $value;
                }
            }

            $booking->status = 'Accepted';

            $booking->transaction = json_encode($request->all());

            unset($booking->id);

            $booking->save();

            BookingTmp::truncate();

            $code = $booking->code;

            $property_price_temp = PropertyPrice::where('property_id',$booking->property_id)->first();

            $days = $this->helper->get_days(setDateForDb($booking->start_date), setDateForDb($booking->end_date));

            for ($j=0; $j<count($days)-1; $j++) {
                $tmp_date = date('Y-m-d', strtotime($days[$j]));

                $property_data = [
                    'property_id' => $booking->property_id,
                    'status'      => 'Not available',
                    'price'       => $property_price_temp->original_price($tmp_date),
                    'date'        => $tmp_date,
                ];

                PropertyDates::updateOrCreate(['property_id' => $booking->property_id, 'date' => $tmp_date], $property_data);
            }

            $this->helper->one_time_message('success', trans('messages.success.payment_success'));
            return redirect('booking/receipt?code=' . $code);

        } else {
            $error = $request->message . ' - Invalid fields: ' . $request->invalid_fields;
            $this->helper->one_time_message('error', $error);

            $property = Properties::find($tmp->property_id);
            BookingTmp::truncate();

            return redirect('properties/' . $property->id . '/' . $property->slug);
        }
    }
}
