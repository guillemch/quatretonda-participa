<iframe
    src="https://www.facebook.com/plugins/like.php?href={{ url('') }}&width=198&layout=button_count&action=like&show_faces=false&share={{ $share }}&height=37&appId={{ config('participa.facebook_app_id') }}&locale=@lang('participa.facebook_locale')"
    width="100"
    allowTransparency="true"
    scrolling="no"
    class="facebook"
    frameBorder="0"
    height="20">
 </iframe>
 <iframe
    src="https://platform.twitter.com/widgets/tweet_button.html?url={{ url('') }}&via={{ config('participa.twitter', 'infoDisedit') }}&related={{ config('participa.twitter', 'infoDisedit') }}&text={{ rawurlencode($slot) }}&lang=@lang('participa.twitter_locale')"
    class="twitter"
    width="120"
    height="20"
    allowTransparency="true"
    scrolling="no"
    frameBorder="0">
 </iframe>
