<?php
/* By Abdullah As-Sadeed */

$example_url_starting_part = 'https://daddylivehd.sx/stream/stream-';
$example_url_ending_part = '.php';

$example_lower_limit = 1;
$example_upper_limit = 100;

$html = '';

if (isset($_POST['crawl'])) {
  if (isset($_POST['url_starting_part']) && isset($_POST['url_ending_part']) && isset($_POST['lower_limit']) && isset($_POST['upper_limit'])) {
    if (!empty($_POST['url_starting_part']) && !empty($_POST['url_ending_part']) && !empty($_POST['lower_limit']) && !empty($_POST['upper_limit'])) {
      $url_starting_part = filter_input(INPUT_POST, 'url_starting_part', FILTER_SANITIZE_STRING);
      $url_ending_part = filter_input(INPUT_POST, 'url_ending_part', FILTER_SANITIZE_STRING);

      $lower_limit = filter_input(INPUT_POST, 'lower_limit', FILTER_SANITIZE_STRING);
      $upper_limit = filter_input(INPUT_POST, 'upper_limit', FILTER_SANITIZE_STRING);

      $lower_limit = intval($lower_limit);
      $upper_limit = intval($upper_limit);

      if ((strpos($url_starting_part, 'https://') === 0 || strpos($url_starting_part, 'http://') === 0) && ($upper_limit > $lower_limit)) {
        $example_url_starting_part = $url_starting_part;
        $example_url_ending_part = $url_ending_part;

        $example_lower_limit = $lower_limit;
        $example_upper_limit = $upper_limit;

        for ($i = $lower_limit; $i <= $upper_limit; $i++) {
          $url = $url_starting_part . $i . $url_ending_part;

          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
          $webpage = curl_exec($curl);
          curl_close($curl);

          if (!$webpage) {
            $title = 'FETCH FAILED';
          } else {
            $dom = new DOMDocument();
            libxml_use_internal_errors(true);
            $dom->loadHTML($webpage);
            libxml_clear_errors();

            $title_node = $dom->getElementsByTagName('title')->item(0);

            if ($title_node) {
              $title = $title_node->nodeValue;
            } else {
              $title = 'NO TITLE';
            }
          }

          $html .= '<li><a target="_blank" rel="nofollow" title="' . $title . '" href="' . $url . '">' . $title . '</a></li>';

          unset($dom);
          gc_collect_cycles();
        }
      }
    }
  }
}
?>

<!DOCTYPE html>
<!-- By Abdullah As-Sadeed -->
<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta charset="UTF-8" />
  <meta name="author" content="Abdullah As-Sadeed" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sadeed Crawler</title>
  <meta name="description" content="Sadeed Crawler" />
  <meta name="keywords" content="Crawler" />
  <style>
    h1 {
      text-align: center;
    }

    form,
    form input[type="submit"] {
      display: inline-block;
      position: relative;
      left: 50%;
      transform: translateX(-50%);
    }

    ul {
      padding: 50px 10px 30px 20px;
    }

    li {
      padding-bottom: 20px;
    }

    .footer {
      text-align: center;
      padding: 10px;
    }
  </style>
</head>

<body lang="en-US">
  <h1>Sadeed Crawler</h1>

  <form method="POST" id="form">
    <label for="url_starting_part">URL Starting Part:</label>
    <input type="text" name="url_starting_part" value="<?php echo $example_url_starting_part; ?>"
      placeholder="Starting Part of URL" titie="Starting Part of URL" id="url_starting_part" />

    <br />
    <br />

    <label for="url_ending_part">URL Ending Part:</label>
    <input type="text" name="url_ending_part" value="<?php echo $example_url_ending_part; ?>"
      placeholder="Ending Part of URL" titie="Ending Part of URL" id="url_ending_part" />

    <br />
    <br />

    <label for="lower_limit">Lower Limit:</label>
    <input type="number" name="lower_limit" value="<?php echo $example_lower_limit; ?>" placeholder="Lower Limit"
      titie="Lower Limit" id="lower_limit" />

    <br />
    <br />

    <label for="upper_limit">Upper Limit:</label>
    <input type="number" name="upper_limit" value="<?php echo $example_upper_limit; ?>" placeholder="Upper Limit"
      titie="Upper Limit" id="upper_limit" />

    <br />
    <br />

    <input type="submit" name="crawl" value="Crawl" title="Crawl" />
  </form>
  <ul>
    <?php
    echo $html;
    ?>
  </ul>
  <div class="footer">© Abdullah As-Sadeed | <a href="https://github.com/Abdullah-As-Sadeed/Sadeed-Crawler/"
      titie="Get the source code" target="new">Source Code</a></div>
</body>

<script>
  const form = document.getElementById("form");
  form.onsubmit = function (submission) {
    if (form.url_starting_part.value == "") {
      submission.preventDefault();
      alert("Provide URL starting Part !");

      return false;
    } else if (form.url_ending_part.value == "") {
      submission.preventDefault();
      alert("Provide URL ending Part !");

      return false;
    } else if (form.lower_limit.value == "") {
      submission.preventDefault();
      alert("Provide lower limit !");

      return false;
    } else if (form.upper_limit.value == "") {
      submission.preventDefault();
      alert("Provide upper limit !");

      return false;
    } else if (!Number.isInteger(Number(form.lower_limit.value))) {
      submission.preventDefault();
      alert("Lower limit must be an integer !");

      return false;
    } else if (!Number.isInteger(Number(form.upper_limit.value))) {
      submission.preventDefault();
      alert("Upper limit must be an integer !");

      return false;
    } else if (Number(form.lower_limit.value) >= Number(form.upper_limit.value)) {
      submission.preventDefault();
      alert("Upper limit must be greater than lower limit !");

      return false;
    } else {
      form.crawl.value = "Crawling..."
    }
  }

  form.upper_limit.focus();
</script>

</html>

<?php exit(); ?>
