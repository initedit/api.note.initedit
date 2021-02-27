<?php

namespace App\Http\Controllers;

use App\Note\Repositories\Note\NoteInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;

class NoteController extends Controller
{

    public $noteRepositories;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(NoteInterface $noteRepositories)
    {
        $this->noteRepositories = $noteRepositories;
    }

    function fetchRoutes()
    {

        $routes = array(
            array(
                "url" => "/"
            )
        );

        $response = [
            "code" => 200,
            "message" => "All routes",
            "content" => $routes,
            "request_at" => time()
        ];

        return response()->json($response, 200);
    }
    function fetchTab(Request $request, $slug, $tabid)
    {
        $note = $request->note;
        $notes = $this->noteRepositories->getTabById($slug, $tabid);
        if ($notes != null) {
            $info = [
                "type" => $note->type,
                "created_on" => $note->createdon,
                "slug"=>$slug,
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
    }
    function fetchTabs(Request $request, $slug)
    {
        $note = $request->note;
        $tabids = $request->input("ids", "");
        $tabids = explode(",", $tabids);

        $notes = $this->noteRepositories->getTabsByIds($slug, $tabids);
        if ($notes != null) {
            $info = [
                "type" => $note->type,
                "created_on" => $note->createdon,
                "slug"=>$slug,
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
    }
    function auth(Request $request, $slug = null)
    {
        if ($slug != null) {
            $results = $this->noteRepositories->getNoteBySlug($slug);

            if ($results == null) {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => $results,
                    "request_at" => time()
                ];
                return response()->json($response, 404);
            } else {
                $header_token = $this->noteRepositories->encryptPassword($request->header('token'));

                $item_password = $results->password;
                $item_type = $results->type;
                if ($item_type == "Public" || ($item_type == "Protected" && $item_password == $header_token) || ($item_type == "Private" && $item_password == $header_token)) {

                    $info = [
                        "type" => $results->type,
                        "created_on" => $results->createdon,
                        "slug"=>$slug,
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
    function fetch(Request $request, $slug = null)
    {
        $note = $request->note;

        $notes = $this->noteRepositories->getTabsBySlug($slug);

        $info = [
            "type" => $note->type,
            "created_on" => $note->createdon,
            "slug"=>$slug,
        ];

        $response = [
            "code" => 1,
            "error" => "",
            "info" => $info,
            "content" => $notes,
            "request_at" => time()
        ];
        return response()->json($response, 200);
    }

    function addNote(Request $request)
    {
        $slug = $request->json("name", null);
        if ($slug != null) {
            $results = $this->noteRepositories->getNoteBySlug($slug);

            if ($results == null) {
                $header_token = $this->noteRepositories->encryptPassword($request->header('token'));

                $name = $request->json("name", null);
                if (strlen($name) <= 255) {
                    $noteInsert = $request->only("name", "type", "password");
                    if (!$request->has("password")) {
                        $noteInsert['password'] = $header_token;
                    }
                    try {
                        $this->noteRepositories->create($noteInsert);
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
                        "error" => "Slug name should be within 255 charecter",
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
    function updateNote(Request $request, $slug)
    {
        $noteUpdate = $request->only("type", "password");
        if (!$request->has("password")) {
            $noteUpdate['password'] = $request->headerToken;
        }
        $user_tabs = $request->json('items');
        // dump($user_tabs);
        // return response($user_tabs);
        $this->noteRepositories->update($slug, $noteUpdate, $user_tabs);
        $response = [
            "code" => 1,
            "error" => "",
            "request_at" => time()
        ];
        return response()->json($response, 200);
    }

    function addTab(Request $request, $slug)
    {

        $tabInsert = $request->only("title", "content", "visibility", "order_index");
        try {
            $newTab = $this->noteRepositories->addTab($slug, $tabInsert);
            $response = [
                "code" => 1,
                "error" => "",
                "request_at" => time(),
                "tab" => $newTab
            ];
            return response()->json($response, 200);
        } catch (Exception $ex) {
            $response = [
                "code" => 100,
                "error" => "Unable to create note",
                "exception" => $ex,
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 406);
        }
    }

    function addTabs(Request $request, $slug)
    {
        $user_tabs = $request->json('items');
        $addedTab = [];
        foreach ($user_tabs as $tab) {
            try {
                $newTab = $this->noteRepositories->addTab($slug, $tab);
                $addedTab[] = $newTab;
            } catch (Exception $ex) {
                $response = [
                    "code" => 100,
                    "error" => "Unable to create note",
                    "exception" => $ex,
                    "content" => null,
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
    }

    function updateTab(Request $request, $slug, $tabid)
    {

        $notes = $this->noteRepositories->getTabById($slug, $tabid);
        if ($notes != null) {

            $tabUpdate = $request->only("title", "content", "visibility", "order_index");
            $this->noteRepositories->updateTab($slug, $tabid, $tabUpdate);

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
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 404);
        }
    }

    function updateTabs(Request $request, $slug)
    {

        $user_tabs = $request->json('items');

        $updatedTabs = [];

        foreach ($user_tabs as $tab) {
            $tabid = isset($tab["id"]) ? $tab["id"] : null;
            $notes = $this->noteRepositories->getTabById($slug, $tabid);
            if ($notes != null) {

                $updatedTab = $this->noteRepositories->updateTab($slug, $tabid, $tab);
                $response = [
                    "code" => 1,
                    "error" => "",
                    "request_at" => time(),
                    "tabid" => $tabid,
                    'tab'=> $updatedTab,
                    'old_tab' => $tab,
                ];
                $updatedTabs[] = $response;
            } else {
                $response = [
                    "code" => 100,
                    "error" => "Not found",
                    "content" => null,
                    "request_at" => time(),
                    "tabid" => $tabid
                ];
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
    }

    function deleteTab(Request $request, $slug, $tabid)
    {

        $notes = $this->noteRepositories->getTabById($slug, $tabid);
        if ($notes != null) {

            $this->noteRepositories->deleteTab($slug, $tabid);
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
                "content" => null,
                "request_at" => time()
            ];
            return response()->json($response, 404);
        }
    }

    function deleteNote(Request $request, $slug)
    {

        $this->noteRepositories->delete($slug);
        $response = [
            "code" => 1,
            "error" => "",
            "request_at" => time()
        ];
        return response()->json($response, 200);
    }
}
