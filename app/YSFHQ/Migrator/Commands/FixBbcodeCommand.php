<?php namespace YSFHQ\Migrator\Commands;

class FixBbcodeCommand {

    private $old_bbcode_id;
    private $new_bbcode_id;

    public function __construct($old_bbcode_id, $new_bbcode_id)
    {
        $this->old_bbcode_id = $old_bbcode_id;
        $this->new_bbcode_id = $new_bbcode_id;
    }
}
