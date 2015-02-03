<?hh

function updateA($key,
                 $value,
                 $reviewer_unixname = null,
                 $comments = null,
                 $value_check = null,
                 $set_reviewer_required = false,
                 $diff = null,
                 $diff_id = null,
                 $author_unixname = null) {

// Assume, after a bunch of code above this statement, that $comments is still null
create_updateA($key, $comments);
