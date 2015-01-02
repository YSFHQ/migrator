<?php namespace YSFHQ\Infrastructure\Clients;

use \Exception;

class DrupalClient extends DatabaseClient
{

    public function getAddons($page = 1)
    {
        return $this->getConnection('drupal')
            ->table('drupal_node')
            ->join('drupal_content_type_addon', 'drupal_node.nid', '=', 'drupal_content_type_addon.nid')
            ->join('drupal_content_field_prev', 'drupal_node.nid', '=', 'drupal_content_field_prev.nid')
            ->join('drupal_users', 'drupal_node.uid', '=', 'drupal_users.uid')
            ->where('drupal_node.type', '=', 'addon')
            ->where('drupal_node.status', '=', 1)
            ->skip($this->per_page * ($page - 1))
            ->take($this->per_page)
            ->select(
                'drupal_node.nid',
                'drupal_node.title',
                'drupal_content_type_addon.field_modtype_value',
                'drupal_content_type_addon.field_blurb_value',
                'drupal_content_field_prev.field_prev_url',
                'drupal_content_field_prev.field_prev_title',
                'drupal_content_type_addon.field_desc_value',
                'drupal_content_type_addon.field_dl_url',
                'drupal_content_type_addon.field_dl_title',
                'drupal_content_type_addon.field_credit_value',
                'drupal_users.name'
            )->get();
    }

    public function getScreenshots($page = 1)
    {
        return $this->getContent($page, 'screenshot')
            ->select(
                'drupal_node.nid',
                'drupal_node.title',
                'drupal_content_type_screenshot.field_url_url',
                'drupal_content_type_screenshot.field_url_title',
                'drupal_node_revisions.body',
                'drupal_node_revisions.teaser',
                'drupal_users.name'
            )->get();
    }

    public function getVideos($page = 1)
    {
        return $this->getContent($page, 'video')
            ->select(
                'drupal_node.nid',
                'drupal_node.title',
                'drupal_content_type_video.field_vid_embed',
                'drupal_content_type_video.field_vid_value',
                'drupal_content_type_video.field_vid_provider',
                'drupal_content_type_video.field_vid_duration',
                'drupal_node_revisions.body',
                'drupal_node_revisions.teaser',
                'drupal_users.name'
            )->get();
    }

    public function getStories($page = 1)
    {
        return $this->getConnection('drupal')
            ->table('drupal_node')
            ->join('drupal_node_revisions', 'drupal_node.nid', '=', 'drupal_node_revisions.nid')
            ->join('drupal_users', 'drupal_node.uid', '=', 'drupal_users.uid')
            ->where('drupal_node.type', '=', 'story')
            ->where('drupal_node.status', '=', 1)
            ->skip($this->per_page * ($page - 1))->take($this->per_page)
            ->select(
                'drupal_node.nid',
                'drupal_node_revisions.title',
                'drupal_node_revisions.body',
                'drupal_node_revisions.teaser'
            )->get();
    }

    private function getContent($page = 1, $type = null)
    {
        return $this->getConnection('drupal')
            ->table('drupal_node')
            ->join('drupal_node_revisions', 'drupal_node.nid', '=', 'drupal_node_revisions.nid')
            ->join('drupal_content_type_'.$type, 'drupal_node.nid', '=', 'drupal_content_type_'.$type.'.nid')
            ->join('drupal_users', 'drupal_node.uid', '=', 'drupal_users.uid')
            ->where('drupal_node.type', '=', $type)
            ->where('drupal_node.status', '=', 1)
            ->skip($this->per_page * ($page - 1))->take($this->per_page);
    }

}
