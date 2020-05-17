<template>
  <b-modal id="optionModal" ref="optionModal" v-model="active" @hidden="close" class="option-modal">
    <div slot="modal-title" class="title">{{ option.option }}</div>

    <div class="option-modal-body">
      <div v-html="option.description"></div>

      <h4 v-if="option.motivation">{{ $t('option.motivation') }}</h4>
      <div v-html="option.motivation"></div>

      <div v-if="option.cost > 0">
        <h4 class="mb-0">{{ $t('option.cost') }}</h4>
        <span class="option-cost">{{ option.cost | formatCurrency }}</span>
      </div>

      <div v-if="option.attachments">
        <h4>{{ $t('option.attachments') }}</h4>

        <ul class="option-attachments">
          <li v-for="(attachment, key) in attachments" :key="key" >
            <a :href="attachment[1]" target="_blank" rel="noopener">
              <i class="far fa-file-alt" aria-hidden="true" /> {{ attachment[0] }}
            </a>
          </li>
        </ul>
      </div>

      <div v-if="option.pictures" class="option-pictures">
        <div v-for="(picture, key) in pictures" :key="key">
          <img :src="picture[0]" alt="Imatge" />
        </div>
      </div>
    </div>

    <div slot="modal-footer">
      <button @click="close" class="btn btn-secondary">{{ $t('option_modal.dismiss_button') }}</button>
      <button v-if="showSelect && selected" @click="toggleOption" class="btn btn-danger">
        <i class="far fa-window-close" aria-hidden="true"></i> {{ $t('option_modal.deselect_button') }}
      </button>
      <button v-if="showSelect && !selected" @click="toggleOption" class="btn btn-primary">
        <i class="far fa-check-square" aria-hidden="true"></i> {{ $t('option_modal.select_button') }}
      </button>
    </div>
  </b-modal>
</template>

<script>
  import format from 'format-number';

  export default {
    name: 'option-modal',

    data () {
      return {
        active: false,
        option: {},
        type: 'radio',
        showSelect: false,
        selected: false
      }
    },

    filters: {
      formatCurrency: function (value) {
        if(window.BoothConfig.locale == 'es'
        || window.BoothConfig.locale == 'ca') {
          return format({ suffix: '€', integerSeparator: '.', round: 0 })(value);
        }

        return format({ prefix: '€', integerSeparator: ',', round: 0 })(value);
      }
    },

    created () {
      Bus.$on('openOptionModal', (option, type, showSelect, selected) => this.open(option, type, showSelect, selected));
    },

    computed: {
      attachments: function () {
        if (this.option.attachments)
          return this.parseList(this.option.attachments);
      },

      pictures: function () {
        if (this.option.pictures)
          return this.parseList(this.option.pictures);
      },
    },

    methods: {
      open (option, type, showSelect, selected) {
        this.option = option;
        this.type = type;
        this.showSelect = showSelect;
        this.selected = selected;
        this.active = true;
      },

      close () {
        this.active = false;
        Bus.$emit('focusOption', this.option);
      },

      toggleOption () {
        if (this.type === 'radio' && this.selected) {
          Bus.$emit('clearQuestion', this.option);
        } else {
          Bus.$emit('optionSelected', this.option, this.type);
        }
        this.close();
      },

      parseList(list) {
        let lines = list.split('\n');
        let arrayList = [];

        if (list.length === 0) return;

        lines.forEach((line) => {
          arrayList.push(line.split(','));
        });

        return arrayList;
      }
    }
  }
</script>
