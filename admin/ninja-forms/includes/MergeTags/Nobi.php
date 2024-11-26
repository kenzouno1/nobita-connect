<?php if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * Class NF_MergeTags_Other
 */
final class NF_Nobi_MergeTags_Nobi extends NF_Abstracts_MergeTags
{
    protected $id = 'nobi';

    public function __construct()
    {
        parent::__construct();
        $this->title = esc_html__( 'Nobi', 'ninja-forms' );
        $this->merge_tags = NF_Nobi::config("MergeTagsNobi");

    }
} // END CLASS NF_MergeTags_Other
