<?php

namespace App\Note\Repositories\Note;

interface NoteInterface
{
    public function create($note);
    public function update($slug, $note);
    public function delete($slug);
    public function encryptPassword($pass);
    public function getNoteBySlug($slug);
    public function getTabsBySlug($slug);
    public function getTabById($slug, $tabid);
    public function getTabsByIds($slug, $tabids);
    public function addTab($slug, $tab);
    public function updateTab($slug, $tabid, $tab);
    public function deleteTab($slug, $tabid);
}
