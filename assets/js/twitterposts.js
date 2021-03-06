twitterObject = {
  addRemoveTweet : function(obj) {
    obj.live('click', function() {
      var current = jQuery(this);
      twitterObject.removeSelect(obj);
      twitterObject.addSelect(current);
    });
  },
  removeSelect : function(obj) {
    twitterObject.removeAddedTweet();
    jQuery('.tweets-users.modal .profile').removeClass('current');
  },
  addSelect : function(current) {
    twitterObject.addNewTweet(current);
    current.addClass('current');
  },
  addNewTweet : function(current) {
    var data = current.html();
    var html = '';
    html += '<div class="profile current">';
    html += data;
    html += '</div>';
    jQuery('.selected-tweets .tweets-users').append(html);
  },
  removeAddedTweet : function() {
    jQuery('.selected-tweets .tweets-users').empty();
  },
  removeSelected : function(obj) {
    obj.live('click', function() {
      var current = jQuery(this);
      current.remove();
    });
  },
  getTweets : function(obj) {
    obj.live('click', function() {
      var loading = jQuery('.loading');
      loading.css('display', 'block');
      var current = jQuery(this);
      var search = jQuery('.search-tweets');
      var value = search.val();
      var fieldName = search.attr('data-field-name');
      var consumerKey = search.attr('data-consumer-key');
      var consumerSecret = search.attr('data-consumer-secret');
      var accessToken = search.attr('data-access-token');
      var accessSecret = search.attr('data-access-secret');
      if(value.length > 2) {
        jQuery.ajax({
          url : gettweet.ajax_url,
          type : 'post',
          data : { action : 'getTweets', keyword : value, fieldName : fieldName,
          consumerKey : consumerKey, consumerSecret : consumerSecret,
          accessToken : accessToken, accessSecret : accessSecret
      		},
          success : function( response ) {
            loading.hide();
            jQuery('.tweets-users.modal').html(response);
          },
          error : function() {
            alert("Something went wrong!");
          }
        });
      }
    });
  },
  modalDone : function(obj) {
    obj.live('click', function() {
      jQuery('.tweets-users.modal').empty();
    });
  }
}


jQuery(document).ready(function($) {
  //add or remove tweet upon click on the tweet.
  twitterObject.addRemoveTweet($('.tweets-users.modal .profile'));
  //remove the added tweet under 'Select' button when it is clicked
  twitterObject.removeSelected($('.selected-tweets .tweets-users .profile'));
  //trigger when click on search button in the modal box
  //and get tweets from Twitter API through Ajax call
  twitterObject.getTweets($('.media-content .search'));
  //trigger when the Done button is clicked and remove all tweets from modal box
  twitterObject.modalDone($('input.modal-done'));
});
