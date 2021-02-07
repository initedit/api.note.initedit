<?php

namespace App\Note\Repositories\Note;

use Illuminate\Support\Facades\DB;

class NoteRepositories implements NoteInterface
{
    public function encryptPassword($pass)
    {
        $masala = env("APP_SECRET_MASALA");
        return hash("sha256", $masala . $pass);
    }

    public function create($note)
    {
        $name = $note['name'];
        $note_type = $note['type'];
        $password = $note['password'];
        if (empty($note_type)) {
            $note_type = "Public";
        }
        $note_type = ucfirst($note_type);
        $password = $this->encryptPassword($password);

        $noteInsert = [
            "slug" => $name,
            "type" => $note_type,
            "password" => $password,
            "status" => 1,
            "createdon" => time()
        ];
        $objNote = DB::table('notes')
            ->insert($noteInsert);
        return $objNote;
    }
    public function update($slug, $note)
    {
        $note_type = $note['type'];
        $password = $note['password'];
        if (empty($note_type)) {
            $note_type = "Public";
        }
        $note_type = ucfirst($note_type);
        $password = $this->encryptPassword($password);

        $noteUpdate = array();
        if ($note_type) {
            $noteUpdate["type"] = $note_type;
        }
        if ($password) {
            $noteUpdate["password"] = $password;
        }
        $objNote = DB::table("notes")
            ->where("slug", $slug)
            ->update($noteUpdate);;
        return $objNote;
    }

    public function getNoteBySlug($slug)
    {
        return DB::table('notes')->where('slug', $slug)->first();
    }

    public function getTabsBySlug($slug)
    {
        return DB::table("notes_tab")
            ->select("id", "slug", "title", "visibility", "order_index", "createdon", "modifiedon", "status", "parent_id")
            ->where("slug", $slug)
            ->orderBy("order_index", "asc")
            ->get();
    }
    public function getTabById($slug, $tabid)
    {
        return DB::table("notes_tab")
            ->where("slug", $slug)
            ->where("id", $tabid)
            ->first();
    }
    public function getTabsByIds($slug, $tabids)
    {
        return DB::table("notes_tab")
            ->where("slug", $slug)
            ->whereIn("id", $tabids)
            ->get();
    }
    public function addTab($slug, $tab)
    {
        $max_id = DB::table("notes_tab")
            ->where("slug", $slug)
            ->selectRaw("COALESCE(MAX(id),0) as max_id")
            ->pluck("max_id")
            ->first();

        $tab_title = $tab["title"];
        $tab_content = $tab["content"];
        $tab_visibility = $tab["visibility"];
        $tab_order_index = $tab["order_index"];
        if ($tab_visibility == null) {
            $tab_visibility = 1;
        }
        if ($tab_order_index == null) {
            $tab_order_index = 1;
        }

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

        $objTab = DB::table('notes_tab')
            ->insert($tabInsert);
        return $this->getTabById($slug, $tabInsert['id']);
    }

    public function updateTab($slug, $tabid, $tab)
    {

        $tab_title = $tab["title"];
        $tab_content = $tab["content"];
        $tab_visibility = $tab["visibility"];
        $tab_order_index = $tab["order_index"];
        $tabUpdate = [
            "modifiedon" => time()
        ];
        if ($tab_title) {
            $tabUpdate["title"] = $tab_title;
        }
        if ($tab_content) {
            $tabUpdate["content"] = $tab_content;
        }
        if (in_array($tab_visibility, [0, 1], true)) {
            $tabUpdate["visibility"] = $tab_visibility;
        }
        if ($tab_order_index) {
            $tabUpdate["order_index"] = $tab_order_index;
        }
        $updated = DB::table("notes_tab")
            ->where("slug", $slug)
            ->where("id", $tabid)
            ->update($tabUpdate);
        return $updated;
    }

    public function deleteTab($slug, $tabid)
    {

        $deleted = DB::table("notes_tab")
            ->where("slug", $slug)
            ->where("id", $tabid)
            ->delete();
        return $deleted;
    }

    public function delete($slug)
    {
        DB::table("notes_tab")
            ->where("slug", $slug)
            ->delete();
        DB::table("notes")
            ->where("slug", $slug)
            ->delete();
    }
}
