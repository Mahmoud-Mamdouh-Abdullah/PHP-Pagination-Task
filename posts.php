<?php
require_once('config.php');
require_once(BASE_PATH . '/logic/postsLogic.php');
require_once(BASE_PATH . '/logic/categories.php');
$category_id = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : null;
$tag_id = isset($_REQUEST['tag_id']) ? $_REQUEST['tag_id'] : null;
$q = isset($_REQUEST['q']) ? $_REQUEST['q'] : null;
$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
$page_size = 6;
$posts = getPosts($page_size, $page, $category_id, $tag_id, null, $q);
$posts_count = getPostCount($category_id, $tag_id, null, $q);
$page_count = ceil($posts_count / $page_size);

function prepareUrl($p, $category_id, $tag_id, $q)
{
  $url = BASE_URL . "/posts.php?page=$p";
  if ($category_id != null) {
    $url .= "&category_id=$category_id";
  }
  if ($tag_id != null) {
    $url .= "&tag_id=$tag_id";
  }
  if ($q != null) {
    $url .= "&q=$q";
  }
  return $url;
}

?>
<?php require_once('layout/header.php'); ?>

<!-- Page Content -->


<main class="main-container">
  <section class="blog-posts grid-system">
    <div class="container">

      <div class="row mb-5">
        <div class="col-lg-12">
          <form id="search_form" name="gs" method="GET" action="posts.php">
            <input type="text" name="q" class="form-control" placeholder="type to search..." autocomplete="on">
          </form>
        </div>

      </div>

      <div class="row">

        <div class="col-lg-12">
          <div class="all-blog-posts">
            <div class="row">
              <?php
              foreach ($posts as $post) {
              ?>
                <div class="col-lg-6">
                  <div class="blog-post">
                    <div class="blog-thumb">
                      <img src="assets/images/blog-thumb-01.jpg" alt="">
                    </div>
                    <div class="down-content">
                      <span><?= $post['c_name'] ?></span>
                      <a href="<?= BASE_URL . '/post-details.php?id=' . $post['id'] ?>">
                        <h4><?= $post['title'] ?></h4>
                      </a>
                      <ul class="post-info">
                        <li><a href="#"><?= $post['username'] ?></a></li>
                        <li><a href="#"><?= $post['publish_date'] ?></a></li>
                        <li><a href="#"><?= $post['number_of_comment'] ?> Comments</a></li>
                      </ul>
                      <p><?= $post['content'] ?></p>
                      <div class="post-options">
                        <div class="row">
                          <div class="col-lg-12">
                            <ul class="post-tags">
                              <li><i class="fa fa-tags"></i></li>
                              <?php
                              $tags = $post['tags'];
                              foreach ($tags as $tag) {
                              ?>
                                <li><a href="<?= BASE_URL . '/posts.php?tag_id=' . $tag['id'] ?>"><?= $tag['name'] ?></a>,</li>
                              <?php
                              }
                              ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
              }
              ?>

              <div class="col-lg-12">
                <ul class="page-numbers">
                  <?php
                  $prev = prepareUrl($page - 1, $category_id, $tag_id, $q);
                  $next = prepareUrl($page + 1, $category_id, $tag_id, $q);
                  if ($page > 1) {
                  ?>
                    <li><a href="<?=$prev?>"><i class="fa fa-angle-double-left"></i></a></li>
                    <?php
                  }
                  for ($i = 1; $i <= intval($page_count); $i++) {
                    $currentUrl = prepareUrl($i, $category_id, $tag_id, $q);
                    if ($i == $page) {
                    ?>
                      <li class="active"><a href="<?= $currentUrl ?>"><?= $i ?></a></li>
                    <?php
                      continue;
                    }
                    ?>
                    <li><a href="<?= $currentUrl ?>"><?= $i ?></a></li>
                  <?php
                  }
                  if ($page < $page_count) {
                  ?>
                    <li><a href="<?=$next?>"><i class="fa fa-angle-double-right"></i></a></li>
                  <?php
                  }
                  ?>
                </ul>
              </div>

            </div>
          </div>
        </div>

      </div>

    </div>
  </section>
</main>




<?php require_once('layout/footer.php') ?>