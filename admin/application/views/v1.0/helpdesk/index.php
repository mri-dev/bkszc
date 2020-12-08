<div class="helpdesk-videos">
  <h1>Oktató videók <span>Válasszon a bal oldali videók közül, amelyiket meg szeretné tekinteni!</span></h1>
  <div class="row">
    <div class="col-md-3">
      <div class="video-nav">
        <?php foreach( (array)$this->videos as $group => $items ): ?>
        <h3><?=$group?></h3>
        <ul>
        <?php foreach( (array)$items as $id => $item ): ?>
        <li><? if($item['video'] !== false): ?><a href="#<?=$id?>" data-item="<?=$id?>" data-title="<?=$item['title']?>" data-video="<?=$item['video']?>" onclick="playVideo('<?=$item['title']?>', '<?=$item['video']?>', $(this))"><? endif; ?><?=$item['title']?><? if($item['video'] !== false): ?></a><? endif; ?></li>
        <?php endforeach; ?>
        </ul>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="col-md-9">
      <h2 id="video_title"></h2>
      <div id="progress_bar"><div class="progressbar"></div></div>
      <div class="video-holder" >
        <video width="100%" id="video" poster="/src/helpdesk/video_player_poster.jpg"></video>
      </div>
    </div>
  </div>

  <script>
    var player, updateBar, barSize, progressBar;

    $(function(){
      var hash = window.location.hash;
      hash = hash.substring(hash.indexOf("#")+1);
      var el =  $('.video-nav li > a[data-item=\''+hash+'\']');
      barSize = $('#progress_bar').width();
      progressBar = $('#progress_bar').find('.progressbar');
      if( hash != '')
      {
        playVideo(el.data('title'), el.data('video'), el);
      }
    })

    function playVideo( title, url, ele )
    {
      $('.video-nav a.active').removeClass('active');
      ele.addClass('active');
      player = $('#video').get(0);
      $('#video_title').html( title );
      $('#video').find('source').remove();
      var source = document.createElement('source');
      source.setAttribute( 'src', url );
      player.appendChild( source );
      player.load();
      player.setAttribute( 'controls', true );
      player.play();

      player.addEventListener( 'playing', () => 
      {
        updateBar = setInterval( updateVideoProgress, 50 );
      });
    }

    function updateVideoProgress()
    {
      if ( player.paused || player.ended )
      {
        window.clearInterval( updateBar );    
        progressBar.css({
          width: '0px'
        });    
      }

      var size=parseInt( player.currentTime * barSize  / player.duration );
		  progressBar.css({
        width: size+'px'
      });
    }
  </script>
</div>


