<script>
	$('#content-embeddable').embedBlock({
      //The selector(id/class/tagName) inside #element that needs to be processed
      //embedSelector   :'div',
      //Instructs the library whether or not to embed urls
      //link              : true,
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
      //highlightCode     : true,
      //Instructs the library whether or not embed the tweets
      /*
      tweetsEmbed     : true,
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
</script>