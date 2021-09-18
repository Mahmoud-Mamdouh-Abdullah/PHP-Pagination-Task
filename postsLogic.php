<?php
require_once(BASE_PATH . '/DAL/basic_dal.php');

function getPosts($posts_count, $page = 1, $category_id = null, $tag_id = null, $user_id = null, $q = null)
{
    $sql = "SELECT p.*, c.name as c_name, u.username as username
    FROM `posts` p
    JOIN categories c
    ON c.id = p.category_id
    JOIN users u
    ON u.id = p.user_id";
    if ($category_id != null) {
        $sql .= " AND category_id=$category_id";
    }
    if ($user_id != null) {
        $sql .= " AND user_id=$user_id";
    }
    if ($tag_id != null) {
        $sql .= " AND p.id IN (SELECT post_id FROM post_tags WHERE tag_id=$tag_id)";
    }
    if ($q != null) {
        $sql .= " AND (title like '%$q%' OR content like '%$q%' OR username like '%q%')";
    }
    $offset = ($page - 1) * $posts_count;
    $sql .= " ORDER BY p.publish_date LIMIT $offset,$posts_count;";
    $posts = getRows($sql);
    for ($i = 0; $i < count($posts); $i++) {
        $posts[$i]['number_of_comment'] = getPostComments($posts[$i]['id']);
        $posts[$i]['tags'] = getPostTags($posts[$i]['id']);
    }
    return $posts;
}

function getPostCount($category_id = null, $tag_id = null, $user_id = null, $q = null)
{
    $sql = "SELECT COUNT(0) AS posts_counts FROM `posts` p";
    if ($category_id != null) {
        $sql .= " WHERE category_id=$category_id";
    }
    else if ($tag_id != null) {
        $sql .= " WHERE p.id IN (SELECT pt.post_id FROM post_tags pt WHERE pt.tag_id=1);";
    }
    else if ($user_id != null) {
        $sql .= " WHERE p.user_id = $user_id;";
    }
    else if ($q != null) {
        $sql .= " WHERE p.title LIKE '%$q' OR p.content LIKE '%$q';";
    }
    $result = getRow($sql);
    if ($result == null) return 0;
    return $result['posts_counts'];
}

function getPostComments($post_id)
{
    $sql = "SELECT COUNT(0) AS number_of_comment FROM comments WHERE post_id = $post_id;";
    $result = getRow($sql);
    if ($result == null) return 0;
    return $result['number_of_comment'];
}

function getPostTags($post_id)
{
    $sql = "SELECT t.id, t.name FROM post_tags pt
            JOIN tags t 
            ON t.id = pt.tag_id
            WHERE pt.post_id = $post_id;";

    return getRows($sql);
}
