<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
class searchController extends Controller
{
    public function index(Request $request)
    {
        /* get all data from endpoint and store it in array to allow search  */
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.npoint.io/dd85ed11b9d8646c5709');
        $data = json_decode($response->getBody()->getContents(), true);

        /*  get all the parameters from the url */
        $query = $request->all();

        if (isset($query['sort'])) {
            $sort = $query['sort'];
            unset($query['sort']);
        }

        /*  filter array of data by values from url parameters */
        $filtered_array = array_filter($data['hotels'], function ($val) use ($query) {
            /* create array to check result of each paramter */
            $result = [];

            /*   loop through each key and value of param array and compare it to each value in data array */
            foreach ($query as $key => $value) {
                if ($val[$key] == $value) {
                    $result[] = true;
                } elseif ($key == 'availability') {
                    $range_date = explode(':', $value);
                    $from = $range_date[0];
                    /*   check if the value given is range or not  */
                    if (!isset($range_date[1])) {
                        $to = $from;
                    } else {
                        $to = $range_date[1];
                    }

                    /*  filter values in availability array  by given value in url */
                    $filtered = Arr::where($val['availability'], function ($v, $key) use ($from, $to) {
                        return date('Y-m-d', strtotime($v['from'])) <= date('Y-m-d', strtotime($from)) and date('Y-m-d', strtotime($v['to'])) >= date('Y-m-d', strtotime($to));
                    });

                    if (!empty($filtered)) {
                        $result[] = true;
                    } else {
                        $result[] = false;
                    }
                } elseif ($key == 'price') {
                    $range = explode(':', $value);
                    $from_price = $range[0];
                    /*   check if the value given is range or not  */
                    if (isset($range[1])) {
                        $to_price = $range[1];
                        if ($from_price <= $val['price'] && $to_price >= $val['price']) {
                            $result[] = true;
                        } else {
                            $result[] = false;
                        }
                    } else {
                        if ($from_price <= $val['price']) {
                            $result[] = true;
                        } else {
                            $result[] = false;
                        }
                    }
                } else {
                    $result[] = false;
                }
            }
            /*   check if the there is any value not equal in the result array or not as the result arrray contains true and falses  */
            if (in_array(false, $result)) {
                return false;
            } else {
                return true;
            }
        });
        /* check if there is sorting or not */ 
        if (isset($sort)) {
            $collection = collect($filtered_array);

            $collection = $collection->sortBy($sort);

            return $collection->values()->all();
        } else {
            return $filtered_array;
        }
    }
}
