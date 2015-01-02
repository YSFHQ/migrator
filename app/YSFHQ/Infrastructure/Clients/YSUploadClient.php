<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;

class YSUploadClient extends DatabaseClient
{

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
                'files_meta_category',
                'files_version',
                'files.image_url',
                'files.downloads',
                'files.uploader_username',
                'files.upload_timestamp'
            )->get();
    }

    public function transferFiles($legacy_id = null)
    {
        throw new Exception("Unimplemented method");
    }

}
