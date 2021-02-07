<?php

/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;

use App\Note\Repositories\Note\NoteRepositories;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class MigrateNoteToAPI
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class MigrateNoteToAPI extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "migrate2api";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Migrate to API";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(NoteRepositories $noteRepositories)
    {
        try {
            DB::beginTransaction();
            $oldNotes = DB::table("notes_old")->get();
            $tabid = 1;
            foreach ($oldNotes as $oldNote) {
                $slug = $oldNote->slug;
                try {
                    $note = $noteRepositories->getNoteBySlug($slug);
                    $tabCreatedOn = Carbon::parse($oldNote->createdon)->timestamp;
                    if ($note == null) {
                        $insertNew = array(
                            "slug" => $slug,
                            "status" => 1,
                            "createdon" => $tabCreatedOn,
                            "type" => ucfirst($oldNote->type),
                            "password" => $oldNote->password,
                        );
                        $inserted = DB::table("notes")->insert($insertNew);
                        $note = $noteRepositories->getNoteBySlug($slug);
                    }
                    DB::table('notes')
                        ->where(array(
                            "slug" => $slug
                        ))->update(array(
                            "password" => $oldNote->password,
                        ));
                    $tabid = $oldNote->order_index + 1;
                    $insertTab = array(
                        "slug" => $slug,
                        "id" => $tabid,
                        "title" => $oldNote->name,
                        "content" => $oldNote->data,
                        "visibility" => $oldNote->visibility == 0 ? 1 : 0,
                        "order_index" => $oldNote->order_index,
                        "createdon" => $tabCreatedOn,
                        "modifiedon" => null,
                        "status" => 1,
                        "parent_id" => null
                    );
                    $noteTab = $noteRepositories->getTabById($slug, $tabid);
                    if ($noteTab == null) {
                        DB::table("notes_tab")->insert($insertTab);
                    } else {
                        unset($insertTab["id"]);
                        unset($insertTab["slug"]);
                        DB::table("notes_tab")
                            ->where(array(
                                "slug" => $slug,
                                "id" => $tabid,
                            ))
                            ->update($insertTab);
                    }
                } catch (Exception $ex) {
                    DB::rollBack();
                    $this->error("Something went wrong while inserting - " . $oldNote->slug);
                    $this->info($ex->getMessage());
                    return;
                }
            }
            DB::commit();

            $this->info("All notes have been migrated");
        } catch (Exception $e) {
            $this->error("An error occurred");
        }
    }
}
