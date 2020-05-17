<template>
  <div class="row ballot-confirmation" role="status" aria-live="assertive" aria-atomic="true">
    <div class="col-md-12">
      <div class="header">
        <i class="far fa-thumbs-up" aria-hidden="true"></i>
        <h2>{{ $t('booth_receipt.heading') }}</h2>
      </div>
      <div class="receipt" v-if="receipt.ref">
        <div class="success">
          {{ $t('booth_receipt.success') }}
        </div>
        <div class="ballot">
          <a :href="'/ballot/' + receipt.ref" target="_blank">
            <img :src="'/api/ballot/qr/' + receipt.ref" alt="QR code" />
            <h3>{{ receipt.ref }}</h3>
            <i class="far fa-arrow-circle-right" aria-hidden="true" alt="Go to ballot"></i>
          </a>
        </div>
      </div>

      <div class="social">
        {{ $t('booth_receipt.social', { municipality }) }}
      </div>
      <div class="social-plugins">
        <iframe
          title="Facebook"
          :src="'https://www.facebook.com/plugins/like.php?href=' + encodeURI(shareable_url) + '&width=198&layout=button_count&action=like&size=large&show_faces=false&share=true&height=37&appId=180444172483336&locale=ca_ES'"
          width="213"
          allowTransparency="true"
          scrolling="no"
          class="facebook"
          frameBorder="0"
          height="37">
         </iframe>
         <iframe
          title="Twitter"
          :src="'https://platform.twitter.com/widgets/tweet_button.html?size=l&url=' + encodeURI(shareable_url) + '&via=' + twitter + '&related=' + twitter + '&text=' + encodeURI($t('global.tweet')) + '&lang=es'"
          class="twitter"
          width="140"
          height="37"
          allowTransparency="true"
          scrolling="no"
          frameBorder="0">
         </iframe>
      </div>

      <hr aria-hidden="true" />

      <div class="further-actions">
        <a :href="council_url">{{ $t('booth_receipt.back_to_council') }}</a>
        <span aria-hidden="true">·</span>
        <router-link to="/">{{ $t('booth_receipt.back_to_booth') }}</router-link>
      </div>
    </div>
  </div>
</template>

<script>

  export default {
    name: 'booth-receipt',

    props: {
      receipt: {
        type: Object,
        default: {
          ref: '000000'
        }
      }
    },

    data() {
      return {
        municipality: '',
        council_url: '',
        shareable_url: '',
        twitter: ''
      }
    },

    created() {
      this.municipality = window.BoothConfig.name;
      this.council_url = window.BoothConfig.council_url;
      this.shareable_url = window.BoothConfig.url;
      this.twitter = window.BoothConfig.twitter;
      document.title = this.$t('booth_receipt.heading') + ' - ' + window.BoothConfig.app_name;
    }
  }
</script>

<style scoped lang="scss">
  @import '../../sass/_variables';

  .header {
    text-align: center;
    margin-top: 20px;

    i {
      font-size: 6rem;
      color: $gray;
      animation: thumbs-up .75s ease-in-out;
    }

    h2 {
      font-size: 2.25rem;
      color: $gray;
      margin-top: 1rem;
      font-weight: 700;
    }
  }

  .receipt {
    max-width: 500px;
    margin: 3rem auto;
  }

  .success {
    background: $brand-success;
    color: #FFF;
    border-radius: 0.5rem 0.5rem 0 0;
    padding: 0.5rem;
    font-size: 1rem;
    text-align: center;
  }

  .ballot {
    border: 3px $brand-success solid;
    border-top: 0;
    border-radius: 0 0 0.5rem 0.5rem;
    padding: 0 1rem;
    text-align: center;

    h3 {
      display: inline;
      font-family: $font-family-monospace;
      color: $gray-dark;
      margin: 0 0.5rem;
    }

    img {
      width: 75px;
      height: 75px;
    }

    i {
      color: $gray-light;
    }

    a:hover, a:active, a:focus {
      text-decoration: none;
      border-bottom: 1px $gray-light dashed;
      padding-bottom: 0.5rem;
    }
  }

  .social {
    text-align: center;
  }

  .social-plugins {
    text-align: center;
    padding: 1rem 0;

    .facebook {
      vertical-align: middle;
      border: none;
      overflow: hidden;
      width: 213px;
    }

    .twitter {
      border: none;
      overflow: hidden;
      width: 90px;
      height: 37px;
      vertical-align: top;
      margin-left: 8px;
      margin-right: 15px;
    }
  }

  .further-actions {
    text-align: center;
    font-size: 1.25rem;

    a {
      white-space: nowrap;
    }
  }


  @media (min-width: 768px) {
    .success {
      padding: 0.5rem 1.25rem;
      font-size: 1.25rem;
    }
  }
</style>
