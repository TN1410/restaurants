<style>
  .single-restaurant {
    padding: 20px;
    background-color: #f9f9f9;
  }

  .container {
    max-width: 800px;
    margin: 0 auto;
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  .entry-title {
    font-size: 2em;
    margin-bottom: 20px;
    color: #333;
  }

  .entry-content {
    margin-bottom: 20px;
  }

  .entry-content img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
    border-radius: 8px;
  }

  .qr-code {
    margin: 20px 0;
    text-align: center;
  }

  .qr-code h2 {
    margin-bottom: 10px;
    font-size: 1.5em;
    color: #333;
  }

  .qr-code img {
    border: 1px solid #ddd;
    border-radius: 8px;
  }

  .restaurant-meta {
    margin-bottom: 20px;
  }

  .comments-section {
    margin-top: 20px;
  }

  /* Optional: Responsive Styles */
  @media (max-width: 768px) {
    .container {
      padding: 15px;
    }

    .entry-title {
      font-size: 1.5em;
    }

    .qr-code h2 {
      font-size: 1.2em;
    }
  }
</style>
<?php get_header(); ?>
<section class="single-restaurant">
  <div class="container">
    <?php
    if (have_posts()) {
      while (have_posts()) {
        the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <h1 class="entry-title"><?php the_title(); ?></h1>
          <div class="entry-content">
            <?php
            if (has_post_thumbnail()) {
              the_post_thumbnail('large');
            }
            ?>
          </div>
          <div class="qr-code">
            <h2>QR Code for this Restaurant</h2>
            <div class="uk-container">
              <div class="uk-text-center">
                <?php
                // Get the current post permalink
                $permalink = get_the_content();
                // echo "<pre>";
                // print_r($permalink);
                // echo "</pre>";
                // Generate the QR code URL
                $qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($permalink) . '&size=100x100';
                ?>
                <img id="barcode" src="<?php echo esc_url($qr_code_url); ?>" alt="QR Code for <?php echo esc_attr(get_the_title()); ?>" title="QR Code" width="100" height="100" />
              </div>
            </div>
          </div>
        </article>
    <?php
      } // end while
    } // end if
    ?>
  </div>
</section>

<?php get_footer(); ?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
  function generateBarCode() {
    var nric = $('#text').val();
    var url = 'https://api.qrserver.com/v1/create-qr-code/?data=' + nric + '&amp;size=50x50';
    $('#barcode').attr('src', url);
  }
</script>