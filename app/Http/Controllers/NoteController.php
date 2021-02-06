<?php

namespace App\Http\Controllers;

use DB;

class NoteController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    function encryptPassword($pass) {
        $masala = "asd6&876q2)!@mbxcb";
        return hash("sha256", $masala . $pass);
    }
    function fetchRoutes() {

        $routes = array(
            array(
                "url"=>"/"
            )
        );

        $response = [
            "code"=>200,
            "message"=>"All routes",
            "content"=>$routes,
            "request_at" => time()
        ];

        return response()->json($response, 200);
    }
    function fetchTab(\Illuminate\Http\Request $request, $slug, $tabid) {
        if ($slug != null) {
            $results = DB::table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || $item_type == "Protected" || ($item_type == "Private" && $item_password == $header_token)) {

                    $notes = DB::table("notes_tab")
                            ->where("slug", $slug)
                            ->where("id", $tabid)
                            ->get();
                    if ($notes != null) {
                        $info = [
                            "type" => $results->type,
                            "created_on" => $results->createdon
                        ];
                        $response = [
                            "code" => 1,
                            "error" => "",
                            "info" => $info,
                            "content" => $notes,
                            "request_at" => time()
                        ];
                        return response()->json($response, 200);
                    } else {
                        $response = [
                            "code" => 100,
                            "error" => "Not found",
                            "content" => $notes,
                            "request_at" => time()
                        ];
                        return response()->json($response, 404);
                    }
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }
    function fetchTabs(\Illuminate\Http\Request $request, $slug) {
        if ($slug != null) {
            $results = DB::table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || $item_type == "Protected" || ($item_type == "Private" && $item_password == $header_token)) {

                    $tabids = $request->input("ids",[]);
                    $tabids = explode(",",$tabids);

                    $notes = DB::table("notes_tab")
                            ->where("slug", $slug)
                            ->whereIn("id", $tabids)
                            ->get();
                    if ($notes != null) {
                        $info = [
                            "type" => $results->type,
                            "created_on" => $results->createdon
                        ];
                        $response = [
                            "code" => 1,
                            "error" => "",
                            "info" => $info,
                            "content" => $notes,
                            "request_at" => time()
                        ];
                        return response()->json($response, 200);
                    } else {
                        $response = [
                            "code" => 100,
                            "error" => "Not found",
                            "content" => $notes,
                            "request_at" => time()
                        ];
                        return response()->json($response, 404);
                    }
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }
    function auth(\Illuminate\Http\Request $request, $slug = null) {
        if ($slug != null) {
            $results = DB::table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {

                    $info = [
                        "type" => $results->type,
                        "created_on" => $results->createdon
                    ];

                    $response = [
                        "code" => 1,
                        "error" => "",
                        "info" => $info,
                        "request_at" => time()
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Invalid password",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 200);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }
    function fetch(\Illuminate\Http\Request $request, $slug = null) {
        if ($slug != null) {
            $results = DB::table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || $item_type == "Protected" || ($item_type == "Private" && $item_password == $header_token)) {

                    $notes = DB::table("notes_tab")
                            ->select("id", "slug", "title", "visibility", "order_index", "createdon", "modifiedon", "status", "parent_id")
                            ->where("slug", $slug)
                            ->orderBy("order_index","asc")
                            ->get();

                    $info = [
                        "type" => $results->type,
                        "created_on" => $results->createdon
                    ];

                    $response = [
                        "code" => 1,
                        "error" => "",
                        "info" => $info,
                        "content" => $notes,
                        "request_at" => time()
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }

    function addNote(\Illuminate\Http\Request $request) {
        $slug = $request->json("name", null);
        if ($slug != null) {
            $results = DB::connection("mysql_master")->table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $header_token = $this->encryptPassword($request->header('token'));

                $name = $request->json("name", null);
                $note_type = ucfirst($request->json("type", "Public"));
                $password = $this->encryptPassword($request->json("password", $header_token));
                if (strlen($name) <= 255) {
                    $noteInsert = [
                        "slug" => $name,
                        "type" => $note_type,
                        "password" => $password,
                        "status" => 1,
                        "createdon" => time()
                    ];
                    try {
                        DB::connection("mysql_master")->table('notes')
                                ->insert($noteInsert);
                        $response = [
                            "code" => 1,
                            "error" => "",
                            "message" => "Note created",
                            "request_at" => time()
                        ];
                        return response()->json($response, 200);
                    } catch (Exception $ex) {
                        $response = [
                            "code" => 100,
                            "error" => "Unable to create note",
                            "exception" => $ex,
                            "content" => $results,
                            "request_at" => time()
                        ];
                        return response()->json($response, 406);
                    }
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Slug name should be within 100 charecter",
                        "request_at" => time()
                    ];
                    return response()->json($response, 400);
                }
            } else {
                $response = [
                    "code" => 100,
                    "error" => "Not Acceptable",
                    "request_at" => time()
                ];
                return response()->json($response, 409);
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Bad Request",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 400);
        }
    }
    function updateNote(\Illuminate\Http\Request $request, $slug) {
        if ($slug != null) {
            $results = DB::connection("mysql_master")->table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {
                    
                    $note_type = ucfirst($request->json("type", "Public"));
                    $note_password = $this->encryptPassword($request->json("password", $header_token));
                    
                    $noteUpdate = array();
                    
                    if ($note_type) {
                        $noteUpdate["type"] = $note_type;
                    }
                    if ($note_password) {
                        $noteUpdate["password"] = $note_password;
                    }
                    DB::connection("mysql_master")->table("notes")
                            ->where("slug", $slug)
                            ->update($noteUpdate);
                    $response = [
                        "code" => 1,
                        "error" => "",
                        "request_at" => time()
                    ];
                    return response()->json($response, 200);
                    
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }

    function addTab(\Illuminate\Http\Request $request, $slug) {
        if ($slug != null) {
            $results = DB::connection("mysql_master")->table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {

                    $max_id = DB::table("notes_tab")
                            ->where("slug", $slug)
                            ->selectRaw("COALESCE(MAX(id),0) as max_id")
                            ->pluck("max_id")
                            ->first();

                    $tab_title = $request->json("title", null);
                    $tab_content = $request->json("content", null);
                    $tab_visibility = $request->json("visibility", 1);
                    $tab_order_index = $request->json("order_index", 1);


                    $tabInsert = [
                        "id" => $max_id + 1,
                        "slug" => $slug,
                        "title" => $tab_title,
                        "content" => $tab_content,
                        "visibility" => $tab_visibility,
                        "order_index" => $tab_order_index,
                        "status" => 1,
                        "createdon" => time()
                    ];
                    try {
                        DB::connection("mysql_master")->table('notes_tab')
                                ->insert($tabInsert);
                        $response = [
                            "code" => 1,
                            "error" => "",
                            "request_at" => time(),
                            "tab" => $tabInsert
                        ];
                        return response()->json($response, 200);
                    } catch (Exception $ex) {
                        $response = [
                            "code" => 100,
                            "error" => "Unable to create note",
                            "exception" => $ex,
                            "content" => $results,
                            "request_at" => time()
                        ];
                        return response()->json($response, 406);
                    }
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }

    function addTabs(\Illuminate\Http\Request $request, $slug) {
        if ($slug != null) {
            $results = DB::connection("mysql_master")->table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {

                    $user_tabs = $request->json();

                    $addedTab = [];

                    foreach ($user_tabs as $tab) {
                        $max_id = DB::table("notes_tab")
                                ->where("slug", $slug)
                                ->selectRaw("COALESCE(MAX(id),0) as max_id")
                                ->pluck("max_id")
                                ->first();

                        $tab_title = $tab["title"];
                        $tab_content = $tab["content"];
                        $tab_visibility = isset($tab["visibility"]) ? $tab["visibility"] : 1;
                        $tab_order_index = isset($tab["order_index"]) ? $tab["order_index"] : 1;


                        $tabInsert = [
                            "id" => $max_id + 1,
                            "slug" => $slug,
                            "title" => $tab_title,
                            "content" => $tab_content,
                            "visibility" => $tab_visibility,
                            "order_index" => $tab_order_index,
                            "status" => 1,
                            "createdon" => time()
                        ];
                        try {
                            DB::connection("mysql_master")->table('notes_tab')
                                    ->insert($tabInsert);
                            $addedTab[] = $tabInsert;
                        } catch (Exception $ex) {
                            $response = [
                                "code" => 100,
                                "error" => "Unable to create note",
                                "exception" => $ex,
                                "content" => $results,
                                "request_at" => time()
                            ];
                            return response()->json($response, 406);
                        }
                    }
                    $response = [
                        "code" => 1,
                        "error" => "",
                        "request_at" => time(),
                        "tabs" => $addedTab
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }

    function updateTab(\Illuminate\Http\Request $request, $slug, $tabid) {
        if ($slug != null) {
            $results = DB::connection("mysql_master")->table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {

                    $notes = DB::connection("mysql_master")->table("notes_tab")
                            ->where("slug", $slug)
                            ->where("id", $tabid)
                            ->get();
                    if ($notes != null) {
                        $tab_title = $request->json("title", null);
                        $tab_content = $request->json("content", null);
                        $tab_visibility = $request->json("visibility", null);
                        $tab_order_index = $request->json("order_index", null);


                        $tabUpdate = [
                            "modifiedon" => time()
                        ];
                        if ($tab_title) {
                            $tabUpdate["title"] = $tab_title;
                        }
                        if ($tab_content) {
                            $tabUpdate["content"] = $tab_content;
                        }
                        if (in_array($tab_visibility,[0,1])) {
                            $tabUpdate["visibility"] = $tab_visibility;
                        }
                        if ($tab_order_index) {
                            $tabUpdate["order_index"] = $tab_order_index;
                        }
                        DB::connection("mysql_master")->table("notes_tab")
                                ->where("slug", $slug)
                                ->where("id", $tabid)
                                ->update($tabUpdate);
                        $response = [
                            "code" => 1,
                            "error" => "",
                            "request_at" => time()
                        ];
                        return response()->json($response, 200);
                    } else {
                        $response = [
                            "code" => 100,
                            "error" => "Not found",
                            "content" => $notes,
                            "request_at" => time()
                        ];
                        return response()->json($response, 404);
                    }
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }

    function updateTabs(\Illuminate\Http\Request $request, $slug) {
        if ($slug != null) {
            $results = DB::connection("mysql_master")->table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {

                    $user_tabs = $request->json();

                    $updatedTabs = [];

                    foreach ($user_tabs as $tab) {
                        $tabid = isset($tab["id"]) ? $tab["id"] : null;
                        $tab_title = isset($tab["title"])?$tab["title"]:null;
                        $tab_content = isset($tab["content"])?$tab["content"]:null;
                        $tab_visibility = isset($tab["visibility"]) ? $tab["visibility"] : 1;
                        $tab_order_index = isset($tab["order_index"]) ? $tab["order_index"] : 1;

                        $notes = DB::connection("mysql_master")->table("notes_tab")
                                ->where("slug", $slug)
                                ->where("id", $tabid)
                                ->get();
                        if ($notes != null) {

                            $tabUpdate = [
                                "modifiedon" => time()
                            ];
                            if ($tab_title) {
                                $tabUpdate["title"] = $tab_title;
                            }
                            if ($tab_content) {
                                $tabUpdate["content"] = $tab_content;
                            }
                            if (in_array($tab_visibility,[0,1])) {
                                $tabUpdate["visibility"] = $tab_visibility;
                            }
                            if ($tab_order_index) {
                                $tabUpdate["order_index"] = $tab_order_index;
                            }
                            DB::connection("mysql_master")->table("notes_tab")
                                    ->where("slug", $slug)
                                    ->where("id", $tabid)
                                    ->update($tabUpdate);
                            $response = [
                                "code" => 1,
                                "error" => "",
                                "request_at" => time(),
                                "tabid" => $tabid,
                                'tab'=>$tabUpdate,
                                'old_tab'=>$tab,
                                'tab_visibility'=>$tab_visibility!=null
                            ];
                            $updatedTabs[] = $response;
                            //return response()->json($response, 200);
                        } else {
                            $response = [
                                "code" => 100,
                                "error" => "Not found",
                                "content" => $notes,
                                "request_at" => time(),
                                "tabid" => $tabid
                            ];
                            //return response()->json($response, 404);
                            $updatedTabs[] = $response;
                        }
                    }
                    $response = [
                        "code" => 1,
                        "error" => "",
                        "request_at" => time(),
                        "tabs" => $updatedTabs
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }

    function deleteTab(\Illuminate\Http\Request $request, $slug, $tabid) {
        if ($slug != null) {
            $results = DB::connection("mysql_master")->table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {

                    $notes = DB::connection("mysql_master")->table("notes_tab")
                            ->where("slug", $slug)
                            ->where("id", $tabid)
                            ->get();
                    if ($notes != null) {

                        DB::connection("mysql_master")->table("notes_tab")
                                ->where("slug", $slug)
                                ->where("id", $tabid)
                                ->delete();
                        $response = [
                            "code" => 1,
                            "error" => "",
                            "request_at" => time()
                        ];
                        return response()->json($response, 200);
                    } else {
                        $response = [
                            "code" => 100,
                            "error" => "Not found",
                            "content" => $notes,
                            "request_at" => time()
                        ];
                        return response()->json($response, 404);
                    }
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time(),
                        "iasdad"=>$request->header('token')
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }

    function deleteNote(\Illuminate\Http\Request $request, $slug) {
        if ($slug != null) {
            $results = DB::connection("mysql_master")->table('notes')->where('slug', $slug)->first();

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {

                    DB::connection("mysql_master")->table("notes_tab")
                            ->where("slug", $slug)
                            ->delete();
                    DB::connection("mysql_master")->table("notes")
                            ->where("slug", $slug)
                            ->delete();
                    $response = [
                        "code" => 1,
                        "error" => "",
                        "request_at" => time()
                    ];
                    return response()->json($response, 200);
                } else {
                    $response = [
                        "code" => 100,
                        "error" => "Unautheticated or re-authenticate yourself",
                        "content" => null,
                        "request_at" => time()
                    ];
                    return response()->json($response, 401);
                }
            }
        } else {
            $response = [
                "code" => 100,
                "error" => "Unauthorized",
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 403);
        }
    }

}
