<script>
	@if (Auth::check())
	var submitComment = function(e, el)
	{
		e.preventDefault();

		var form = $(el),
			formData = form.serialize();

		form.find('input[type="submit"]').attr('disabled', true).addClass('disabled');

		var input     = $.trim(form.find('textarea').first().val()),
			threadId  = form.find('input[name="thread_id"]').first().val(),
			parentId  = form.find('input[name="parent_id"]').first().val(),
			token     = form.find('input[name="_token"]').first().val(),
			usernameRegex = /(?:^|)(\/u\/\w+)(?!\w)/g,
			tagRegex      = /(?:^|)#(\w+)(?!\w)/g;

		// Create links of /u/usernames and #tags
		var markdown = input.replace(usernameRegex, "[$1]($1)").replace(tagRegex, "[#$1](/t/$1)");

		$.post('{{ url("/comment") }}', {_token:token, markdown:markdown, thread_id:threadId, parent_id:parentId})
		.done(function(res)
		{
			var md = markdown,
				ht = marked(md)
				hier = (form.data('hierarchy') == 'parent') ? 'child' : 'parent';

			var commentCount = $('#threadCommentCount'),
				count = parseInt(commentCount.text()) + 1;

			if (count == 1)
				$('#threadCommentPlural').text('');
			else
				$('#threadCommentPlural').text('s');

			commentCount.text(count);

			if (form.parent().hasClass('r-b'))
				form.parent().hide();

			if (res['parentMomentum'] != 'whoopie')
			{
				if (res['parentMomentum'] == 1)
					var parentMomentum = '1 point';
				else
					var parentMomentum = res['parentMomentum'] + ' points';

				form.parent().parent().find('.comment-momentum').first().text(parentMomentum);
			}

			var html = '<article class="comment ' + form.data('hierarchy') + '">';
				html += '<header>';
				html += '<span class="collapser"><i class="ion-chevron-up"></i></span>';
				html += '<span><a href="{{ Auth::user()->permalink() }}">';
				@if ($threadUserId != null && Auth::id() == $threadUserId)
				html += '<span class="username-tag op">OP</span> ';
				@endif
				html += '{{ Auth::user()->username }}</a></span>';
				html += '&middot;';
				html += '<span>0 points</span>';
				html += '&middot;';
				html += '<span class="livestamp"></span>';
				html += '</header>';
				html += '<div class="body">';
				html += '<section class="markdown content-embeddable">';
				html += ht;
				html += '</section>';
				html += '<footer>';
				html += '<a onclick="toggleReplyBox(this)">reply</a>';
				html += '<a href="'+res['permalink']+'">permalink</a>';
				html += '<a href="'+res['context']+'">context</a>';
				html += '</footer>';
				html += '<div class="reply-box r-b">';
				html +=	'<form method="POST" action="{{ url("/comment") }}" accept-charset="UTF-8" class="row comment-box" data-hierarchy="'+hier+'" onsubmit="submitComment(event, this)">';
				html += '<input name="_token" type="hidden" value="{{ csrf_token() }}">';
				html += '<input name="thread_id" type="hidden" value="' + res['threadId'] + '">';
				html += '<input name="parent_id" type="hidden" value="' + res['commentId'] + '">';
				html += '<div class="medium-5 large-3 columns">';
				html += '<p class="no-margin">';
				html += 'You can use <a href="{{ url("/") }}"</a>.';
				html += '</p>';
				html += '<textarea rows="4" name="markdown" cols="50" class="comment-textarea"></textarea>';
				html += '<div class="preview hide">';
				html += '<h6 class="super-header">Live Preview</h6>';
				html += '<div class="markdown"></div>';
				html += '</div>';
				html += '<p class="text-alert"></p>';
				html += '<input class="btn" type="submit" value="Reply">';
				html += '</div>';
				html += '</form>'
				html += '</div>';
				html += '<div class="children"></div>';
				html += '</div>';
				html += '</article>';

			form.parent().parent().find('.children').first().prepend(html);
			form.trigger('reset');

			form.find('.preview').addClass('hide');
			form.find('.markdown').html('');
			form.find('input[type="submit"]').attr('disabled', false).removeClass('disabled');

			var livestamp = $('.livestamp').first();
			livestamp.livestamp(new Date());
			livestamp.removeClass('livestamp');

			if (!form.hasClass('parent-commenter'))
				form.parent().hide();

			form.parent().parent().find('.children .content-embeddable').first().embedBlock({
		      //The selector(id/class/tagName) inside #element that needs to be processed
		      //embedSelector   :'div',
		      //Instructs the library whether or not to embed urls
		      link              : false,
		      //same as the target attribute in html anchor tag . supports all html supported target values.
		      //linkTarget        : '_blank',
		      //Array of extensions to be excluded from converting into links
		      //linkExclude       : ['jpg','pdf'],
		      //set false to show a preview of document(pdf,xls,xlsx,doc,docx,ppt) links
		      docEmbed          : true,
		      docOptions        : {
		              viewText    : '<i class="fa fa-eye"></i> View PDF',
		              downloadText: '<i class="fa fa-download"></i> DOWNLOAD'
		      },
		      //set false to embed images
		      imageEmbed        : true,
		      //set true to enable lightboxes for images
		      imageLightbox     : true,
		      //set false to embed audio
		      audioEmbed        : false,
		      //set false to show a preview of youtube/vimeo videos with details
		      videoEmbed        : true,
		      //set false to show basic video files like mp4 etc. (supported by html5 player)
		      basicVideoEmbed   : true,
		      //width of the video frame (in pixels)
		      videoWidth        : 640,
		      //height of the video frame (in pixels)
		      videoHeight       : 390,
		      //( Mandatory ) The authorization key obtained from google's developer console for
		      //using youtube data api and map embed api
		      gdevAuthKey         : 'AIzaSyD-1RoxCSkWat6q9FZfLykk2lDBrIz5lVY',
		      //Set google map location embed
		      // Use @(place-name) to use this feature . Eg: @(Sydney)
		      locationEmbed       :true,
		      mapOptions        : {
		            //'place' or 'streetview' or 'view'
		            mode: 'place'
		      },
		      //Instructs the library whether or not to highlight code syntax.
		      highlightCode     : false,
		      //Instructs the library whether or not embed the tweets
		      tweetsEmbed     : false,
		      /*
		      tweetOptions:{
		            //The maximum width of a rendered Tweet in whole pixels. Must be between 220 and 550 inclusive.
		            maxWidth   : 550,
		            //When set to true or 1 links in a Tweet are not expanded to photo, video, or link previews.
		            hideMedia  : false,
		            //When set to true or 1 a collapsed version of the previous Tweet in a conversation thread
		            //will not be displayed when the requested Tweet is in reply to another Tweet.
		            hideThread : false,
		            //Specifies whether the embedded Tweet should be floated left, right, or center in
		            //the page relative to the parent element.Valid values are left, right, center, and none.
		            //Defaults to none, meaning no alignment styles are specified for the Tweet.
		            align      : 'none',
		            //Request returned HTML and a rendered Tweet in the specified.
		            //Supported Languages listed here (https://dev.twitter.com/web/overview/languages)
		            lang       : 'en'
		      },
		      */
		      //An array of services excluded from embedding...
		      //Options : codePen/jdFiddle/jsBin/ideone/plunker/soundcloud/twitchTv/dotSub/dailymotion/vine/ted/liveleak/spotify/ustream
		      //          /flickr/instagram
		      //Can exclude all options by setting it to 'all'
		      //excludeEmbed     :['twitchTv'],
		      //Height of jsfiddle/codepen/jsbin/ideone/plunker
		      codeEmbedHeight : 300,
		      soundCloudOptions: {
		          height      : 160,
		          themeColor  : 'f50000',    //Hex Code of the player theme color
		          autoPlay    : false,
		          hideRelated : false,
		          showComments: true,
		          showUser    : true,        //Show or hide the uploader name, useful e.g. in tiny players to save space)
		          showReposts : false,
		          visual      : false,       //Show/hide the big preview image
		          download    : false        //Show/Hide download buttons
		      },
		      vineOptions:{
		            maxWidth:null,
		            type:'postcard',         //'postcard' or 'simple' embedding
		            responsive:false         // whether to make the vine embed responsive
		      }//,
		      //callback before doc preview
		      //beforeDocPreview  : function(){},
		      //callback after doc preview
		      //afterDocPreview   : function(){},
		      // callback on video frame view
		      //onVideoShow:function(){},
		      //callback on video load (youtube/vimeo)
		      //onVideoLoad:function(){}
		      //function to execute before embedding services
		      //beforeEmbedJSApply: function () {},
		      //callback after embedJS is applied
		      //afterEmbedJSLApply: function () {},
		      //callback after the twitter widgets of a BLOCK are loaded.
		      //onTwitterShow     : function () {}
			});

		  $('span.emoticon').each(function(i, el)
		  {
		  	var parent = $(el).parent();
		    var emo = $(el).detach();

		    parent.empty().append(emo);
		  });

		  $('.ne-image-wrapper').each(function(i, el)
		  {
		    var img = $(el).children('img').detach();

		    $(el).empty().append(img);
		  });
		})
		.fail(function(res)
		{
			form.parent().find('.text-alert').first().text(res.responseText);
		});
	}

	$(document).on('keyup', '.comment-textarea, .comment-editor-textarea', function()
	{
		var input = $.trim($(this).val());

		if (input == "")
		{
			$(this).parent().find('.preview').addClass('hide');
		}
		else
		{
			// Create links of /u/usernames and #tags
			var usernameRegex = /(?:^|)(\/u\/\w+)(?!\w)/g,
				tagRegex      = /(?:^|)#(\w+)(?!\w)/g;

			var markdown = input.replace(usernameRegex, "[$1]($1)").replace(tagRegex, "[#$1](/t/$1)"),
				ht    	 = marked(markdown);

			$(this).parent().find('.markdown').html(ht);

			if ($(this).parent().find('.preview').first().hasClass('hide'))
				$(this).parent().find('.preview').removeClass('hide');
		}
	});

	var toggleReplyBox = function(el)
	{
		$(el).parent().parent().find('.reply-box').first().toggle();
	};

	$('.edit-comment').click(function()
	{
		var input = $(this).parent().parent().find('section'),
			i 	  = 0,
			a     = $(this);

		if (a.text() == 'edit')
			a.text('cancel').addClass('text-alert');
		else
			a.text('edit').removeClass('text-alert');

		$.each(input, function(i, el)
		{
			if (i < 2)
			{
				var element = $(el);

				if (element.hasClass('hide'))
					element.removeClass('hide');
				else
					element.addClass('hide');
			}

			i++;
		});
	});

	$('.edit-comment-form').submit(function(e)
	{
		e.preventDefault();

		var submitBtn = $(this).find('input[type="submit"]'),
			error     = $(this).find('.text-alert'),
			markdown  = $(this).find('textarea').first().val(),
			loader    = $(this).find('.loader'),
			form      = $(this),
			data      = form.serialize();

		submitBtn.attr('disabled', true).addClass('disabled');
		loader.addClass('open');

		$.post("{{ url('/me/edit/comment') }}", data)
		.done(function()
		{
			var oc = form.parent().parent().find('.markdown').first(),
				fo = form.parent(),
				eb = form.parent().parent().find('a.edit-comment').first();

			var usernameRegex = /(?:^|)(\/u\/\w+)(?!\w)/g,
				tagRegex      = /(?:^|)#(\w+)(?!\w)/g;

			oc.html(marked(markdown.replace(usernameRegex, "[$1]($1)").replace(tagRegex, "[#$1](/t/$1)"))).removeClass('hide');
			fo.addClass('hide');
			form.trigger('reset');
			submitBtn.attr('disabled', false).removeClass('disabled');
			loader.removeClass('open');
			form.find('textarea').val(markdown);
			eb.text('edit').removeClass('text-alert');
		})
		.fail(function(res)
		{
			error.text(res.responseText);
		});
	});
	@endif

	$('.collapser').click(function()
	{
		var comment = $(this).parent().parent();

		if (comment.hasClass('collapsed'))
		{
			$(this).find('i').removeClass('ion-chevron-down').addClass('ion-chevron-up');
			comment.removeClass('collapsed');
		}
		else
		{
			$(this).find('i').removeClass('ion-chevron-up').addClass('ion-chevron-down');
			comment.addClass('collapsed');
		}
	});
</script>