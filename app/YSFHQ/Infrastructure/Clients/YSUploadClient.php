<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;

class YSUploadClient extends DatabaseClient
{

    public function __construct($per_page = 100, $page = 1)
    {
        parent::__construct($per_page, $page);
    }

    public function getFileMeta($page = 1)
    {
        return $this->getConnection('ysupload')
            ->table('files')
            ->where('status', '=', 'Available')
            ->skip($this->per_page * ($page - 1))->take($this->per_page)
            ->select(
                'files.id',
                'files.filename',
                'files.meta_name',
                'files.meta_desc',
                'files.meta_category',
                'files.version',
                'files.image_url',
                'files.downloads',
                'files.modPerms',
                'files.uploader_username',
                'files.upload_timestamp'
            )->get();
    }

    public function getFileDataFromId($legacy_id = null)
    {
        $result = $this->getConnection('ysupload')
            ->table('files')
            ->where('id', $legacy_id)
            ->first();
        // do some other things like getting filesize, etc.
        return $result;
    }

}
