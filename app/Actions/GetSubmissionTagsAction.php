<?php

namespace App\Actions;

use App\Models\Tag;
use App\Models\TagRelation;

class GetSubmissionTagsAction
{
    public function handle($submission) {
        return $submission->tags()->join('tags', 'tag_relations.tag_id', '=', 'tags.id')->get();
    }
}
