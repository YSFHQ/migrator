<?php

namespace YSFHQ\Infrastructure\Clients;

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
        $file = $this->getConnection('ysupload')->table('files')
            ->where('id', $legacy_id)->first();
        if ($file) {
            $file->local_path = '/var/www/ysupload.com/files/'.substr($file->filename, strpos($file->filename, '/files/')+7);

            $file->filename =  substr($file->local_path, strrpos($file->local_path, '/')+1);
            if (preg_match("/^[0-9]{4}/", $file->filename)) {
                $file->filename = substr($file->filename, 4);
            }

            $file->extension = substr($file->filename, strrpos($file->filename, '.')+1);

            try {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $file->mimetype = finfo_file($finfo, $file->local_path);

                $file->filesize = filesize($file->local_path);
            } catch (\Exception $e) {
                return null;
            }
        }
        return $file;
    }

}
