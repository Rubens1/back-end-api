<?php

namespace App\Http\Controllers\Frete;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;

class MelhorEnvioController extends Controller
{
    //Cotação do produto
    public function cotacao(Request $request){
        try {
            $url = 'https://sandbox.melhorenvio.com.br/api/v2/me/shipment/calculate';

            $body = [
                "from" => [
                    "postal_code" => $request->from
                ],
                "to" => [
                    "postal_code"=> $request->to
                ],
                  "package" => [
                    "height"=> $request->height,
                    "width"=> $request->width,
                    "length"=> $request->length,
                    "weight"=> $request->weight
                  ]
            ];
              
              $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5NTYiLCJqdGkiOiI4NmZjZTc0ZjA0ZmQ2ZDFlZjliOTMwOGE4YmZhNWVlN2Q5OTMzZjAxM2NjYmNjZjZmMTI4NzQ5MjYwYWI2MjhlMGNjNzY5Y2Q0YjIxODRmYiIsImlhdCI6MTcwMTE5NjM4NS4yMjY4MzQsIm5iZiI6MTcwMTE5NjM4NS4yMjY4MzYsImV4cCI6MTczMjgxODc4NS4xOTU3NSwic3ViIjoiOWFiOGMzMTYtNDdmYS00NDY0LTljZjAtNzYxOTRiNzBlYWM1Iiwic2NvcGVzIjpbImNhcnQtcmVhZCIsImNhcnQtd3JpdGUiLCJjb21wYW5pZXMtcmVhZCIsImNvbXBhbmllcy13cml0ZSIsImNvdXBvbnMtcmVhZCIsImNvdXBvbnMtd3JpdGUiLCJub3RpZmljYXRpb25zLXJlYWQiLCJvcmRlcnMtcmVhZCIsInByb2R1Y3RzLXJlYWQiLCJwcm9kdWN0cy1kZXN0cm95IiwicHJvZHVjdHMtd3JpdGUiLCJwdXJjaGFzZXMtcmVhZCIsInNoaXBwaW5nLWNhbGN1bGF0ZSIsInNoaXBwaW5nLWNhbmNlbCIsInNoaXBwaW5nLWNoZWNrb3V0Iiwic2hpcHBpbmctY29tcGFuaWVzIiwic2hpcHBpbmctZ2VuZXJhdGUiLCJzaGlwcGluZy1wcmV2aWV3Iiwic2hpcHBpbmctcHJpbnQiLCJzaGlwcGluZy1zaGFyZSIsInNoaXBwaW5nLXRyYWNraW5nIiwiZWNvbW1lcmNlLXNoaXBwaW5nIiwidHJhbnNhY3Rpb25zLXJlYWQiLCJ1c2Vycy1yZWFkIiwidXNlcnMtd3JpdGUiLCJ3ZWJob29rcy1yZWFkIiwid2ViaG9va3Mtd3JpdGUiLCJ3ZWJob29rcy1kZWxldGUiLCJ0ZGVhbGVyLXdlYmhvb2siXX0.d4ywLh_wm3ag5SERG9nA81pO0CZwpfbi4bt9uRlxSIqOVLWQ6H7Hpa71D8yMGqQlum9jwiu4aDgoWEvQKX6V8fVkL-QlgOtC_V9GD_35ggbzLjN9JAaIDKBNIcoXPzdgdCaSr6sMq-3U1HgJkbZoHgl30ceVbxkvHyiAsA0DPHGWoW6u263sUnS5zGTpRQAyNxZ6qdrKOMN9Em1lURCIoCGXXDwFOJ_I0j14GJiJ697MHLm_DcDSAVW9jtasEz0jnk7mMHs3mtfKmazYhJFuF-I77Yw1iQcQFq_1oT4rspCi0ug2JMb6WzYrUDaXCmigF47x-h6nrfLINOyck2Zf2zXXqHu4iUkE4pFH4vBXPf0Zb-xezHr-QpLkmrlnI6shl5YGmIdKRKf9Gcb24oSFjxiZyw36OpJ3FCjbfsHel114ND641iorKRuExSBzOx7XTAE4QSpka1FIWGnJWgzAiLezW1YATiBNdwCDYzr1I6gU4MMEZcv1bhfRSuiwJCzBAx8Yxgc52skpgX8P89zRRAPk0Gwi--kViRISUW6p03CBQnT2E3-18q9bC4UQ6kz6EYXf8MZkepnZ4vEBq21YOAbXSCf_UaIUAiOBV9qzo43qOygb7C_mpkOnQErK43mV_jI7XM0oy0NGtVfkUAUK1fsaiznoNmZDCpWpRkdzzVI",
                ]
            ])->post($url, $body);

            
              $data = $response->json();
              return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
       
    }

    //Carrinho
    public function carrinho(Request $request){
        try {
            $url = 'https://sandbox.melhorenvio.com.br/api/v2/me/cart';

            $body = [
                "service" => $request->service,
                
                "from" => [
                    "name" => $request->from_name,
                    "address" => $request->from_address,
                    "city" => $request->from_city,
                    "postal_code" => $request->from_postal_code,
                    "phone" => $request->from_phone,
                    "company_document" => $request->cnpj,
                ],
                "to" => [
                    "name" => $request->to_name ,
                    "address" => $request->to_address,
                    "city" => $request->to_city,
                    "postal_code" => $request->to_postal_code,
                    "phone" => $request->to_phone,
                    "document" => $request->cpf,
                ],
                "packages" => [
                    [
                        "price" => $request->price,
                        "discount" => $request->discount,
                        "format"=> $request->format,
                        "dimensions" => [
                            "height" => $request->height,
                            "width" => $request->width,
                            "length" => $request->length
                        ],
                        "weight" => $request->weight,
                        "insurance_value" => $request->insurance_value,
                        "products" => [
                            [
                                "id" => $request->id,
                                "quantity" => $request->quantity
                            ]
                        ]
                    ]
                ],
                "volumes" => [
                    "height" => $request->volumes_height,
                    "width" => $request->volumes_width,
                    "length" => $request->volumes_length,
                    "weight" => $request->volumes_weight
                ],
                "options" => [
                    "insurance_value" => $request->options_value,
                    "receipt" => true,
                    "own_hand" => true,
                    "reverse" => true,
                    "non_commercial" => true
                ]
            ];

            $response = Http::withoutVerifying()->withOptions([
                'headers' => [
                    "Authorization" => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5NTYiLCJqdGkiOiI4NmZjZTc0ZjA0ZmQ2ZDFlZjliOTMwOGE4YmZhNWVlN2Q5OTMzZjAxM2NjYmNjZjZmMTI4NzQ5MjYwYWI2MjhlMGNjNzY5Y2Q0YjIxODRmYiIsImlhdCI6MTcwMTE5NjM4NS4yMjY4MzQsIm5iZiI6MTcwMTE5NjM4NS4yMjY4MzYsImV4cCI6MTczMjgxODc4NS4xOTU3NSwic3ViIjoiOWFiOGMzMTYtNDdmYS00NDY0LTljZjAtNzYxOTRiNzBlYWM1Iiwic2NvcGVzIjpbImNhcnQtcmVhZCIsImNhcnQtd3JpdGUiLCJjb21wYW5pZXMtcmVhZCIsImNvbXBhbmllcy13cml0ZSIsImNvdXBvbnMtcmVhZCIsImNvdXBvbnMtd3JpdGUiLCJub3RpZmljYXRpb25zLXJlYWQiLCJvcmRlcnMtcmVhZCIsInByb2R1Y3RzLXJlYWQiLCJwcm9kdWN0cy1kZXN0cm95IiwicHJvZHVjdHMtd3JpdGUiLCJwdXJjaGFzZXMtcmVhZCIsInNoaXBwaW5nLWNhbGN1bGF0ZSIsInNoaXBwaW5nLWNhbmNlbCIsInNoaXBwaW5nLWNoZWNrb3V0Iiwic2hpcHBpbmctY29tcGFuaWVzIiwic2hpcHBpbmctZ2VuZXJhdGUiLCJzaGlwcGluZy1wcmV2aWV3Iiwic2hpcHBpbmctcHJpbnQiLCJzaGlwcGluZy1zaGFyZSIsInNoaXBwaW5nLXRyYWNraW5nIiwiZWNvbW1lcmNlLXNoaXBwaW5nIiwidHJhbnNhY3Rpb25zLXJlYWQiLCJ1c2Vycy1yZWFkIiwidXNlcnMtd3JpdGUiLCJ3ZWJob29rcy1yZWFkIiwid2ViaG9va3Mtd3JpdGUiLCJ3ZWJob29rcy1kZWxldGUiLCJ0ZGVhbGVyLXdlYmhvb2siXX0.d4ywLh_wm3ag5SERG9nA81pO0CZwpfbi4bt9uRlxSIqOVLWQ6H7Hpa71D8yMGqQlum9jwiu4aDgoWEvQKX6V8fVkL-QlgOtC_V9GD_35ggbzLjN9JAaIDKBNIcoXPzdgdCaSr6sMq-3U1HgJkbZoHgl30ceVbxkvHyiAsA0DPHGWoW6u263sUnS5zGTpRQAyNxZ6qdrKOMN9Em1lURCIoCGXXDwFOJ_I0j14GJiJ697MHLm_DcDSAVW9jtasEz0jnk7mMHs3mtfKmazYhJFuF-I77Yw1iQcQFq_1oT4rspCi0ug2JMb6WzYrUDaXCmigF47x-h6nrfLINOyck2Zf2zXXqHu4iUkE4pFH4vBXPf0Zb-xezHr-QpLkmrlnI6shl5YGmIdKRKf9Gcb24oSFjxiZyw36OpJ3FCjbfsHel114ND641iorKRuExSBzOx7XTAE4QSpka1FIWGnJWgzAiLezW1YATiBNdwCDYzr1I6gU4MMEZcv1bhfRSuiwJCzBAx8Yxgc52skpgX8P89zRRAPk0Gwi--kViRISUW6p03CBQnT2E3-18q9bC4UQ6kz6EYXf8MZkepnZ4vEBq21YOAbXSCf_UaIUAiOBV9qzo43qOygb7C_mpkOnQErK43mV_jI7XM0oy0NGtVfkUAUK1fsaiznoNmZDCpWpRkdzzVI",
                    "User-Agent" => "rubens.jesus1997@gmail.com",
                    "Content-Type" =>  "application/json",
                    "Accept" => "application/json"
                    ]
            ])->post($url, $body);

            $data = $response->json();
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
