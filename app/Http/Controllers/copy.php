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
        /*  $response = Http::get('https://api.npoint.io/dd85ed11b9d8646c5709');
        $data = json_decode($response->body(), true);
        dd($data); */
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'https://api.npoint.io/dd85ed11b9d8646c5709');
        $data = json_decode($response->getBody()->getContents(), true);

        $query = $request->all();
        /*        $collection = collect( json_decode( $response->getBody(), true ) );
         $sortedByPrice = $collection->sortBy('price'); */

        /*  $response = $client->request('GET', 'https://api.npoint.io/dd85ed11b9d8646c5709', [
            'query' => [
                'city' => 'dubai',
            ],
        ]);
    
        if($response->getStatusCode() == 200) {
            return $response->getBody()->getContents();

        } */
        /*   $var1 = $query['city'];
        $var2 = $query['price'];
        $var3 = $query['name'];
        $var4 = $query['availability']; */
        if (isset($query['sort'])) {
            $sort = $query['sort'];
            unset($query['sort']);
        }
        $filtered_array = array_filter($data['hotels'], function ($val) use ($query) {
            /*  return $val['city'] == $query['city'] and $val['name'] == $query['name']; */

            $result = [];

            foreach ($query as $key => $value) {
                if ($val[$key] == $value) {
                    $result[] = true;
                } elseif ($key == 'availability') {
                    $range_date = explode(':', $value);
                    $from = $range_date[0];
                    if (!isset($range_date[1])) {
                        $to = $from;
                    } else {
                        $to = $range_date[1];
                    }

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

            if (in_array(false, $result)) {
                return false;
            } else {
                return true;
            }

            /* return $result ; */
            /*       if (isset($query['availability'])) {
                foreach ($val['availability'] as $v) {

                    if (strtotime($v['from']) <= strtotime($query['availability']) && strtotime($v['to']) >= strtotime($query['availability'])) { */
            /*                         if ($val['city'] == $query['city'] && $val['price'] == $query['price'] && $val['name'] == $query['name']) {
             */ /* $x = true; */
            /*  } */
            /*    } else {
                        $x = false;
                    }
                }
            } else {
                $x = false;
            } */
            /* if (isset($query['price'])) {
                $range = explode(':', $query['price']);
                if ($range[0] <= $val['price'] && $range[1] >= $val['price']) {
                    $y = true;
                } else {
                    $y = false;
                }
            } else {
                $y = false;
            } */

            /*     return $val['city'] == $query['city'] and $val['name'] == $query['name'] and $x and $y; */
        });
        if (isset($sort)) {
            $collection = collect($filtered_array);

            $collection = $collection->sortBy($sort);

            dd($collection->values()->all());
        } else {
            dd($filtered_array);
        }

        /*   $var1 = $query['city'];
        $var2 = $query['price'];
        $var3 = $query['name'];
        $var4 = $query['availability'];
 */

        /*  if ($search) {
            $take = Client::table->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            });
        } */
        /*  return 'referr'; */
    }
}
