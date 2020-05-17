<template>
  <b-modal
    id="annulBallot"
    ref="annulBallot"
    @shown="focus('SID')"
    @hidden="clear">
    <div slot="modal-title">
      <span class="title">Anul·la una papereta</span>
    </div>

    <div v-if="success">
      <div class="alert alert-success">
        <i class="far fa-check" aria-hidden="true"></i> Papereta anul·lada correctament.
      </div>
    </div>

    <form @submit.prevent="ballotLookup" v-if="!success">
      <div class="form-group">
        <label for="SID">Identificador</label>
        <input
          v-if="step === 1"
          type="text"
          ref="SID"
          id="SID"
          v-model="SID"
          v-focus="focused === 'SID'"
          :class="{
            'form-control': true,
            'is-invalid': errors.hasOwnProperty('SID')
          }"
          placeholder="DNI, NIF o Passaport" />
        <p v-if="step === 2">
          <strong>{{ SID }}</strong>
          <a href="#" @click.prevent="back">
            <i class="far fa-pencil-alt" aria-hidden="true"></i>
          </a>
        </p>
        <div v-if="errors.hasOwnProperty('SID')" class="invalid-feedback">
          <div
            v-for="(error, key) in errors.SID"
            :key="key">
            {{ error }}
          </div>
        </div>
      </div>

      <div v-if="step === 2">
        <div class="form-group">
          <label for="reason" class="mb-0">Justificació</label>
          <small class="form-text text-muted mt-0 mb-1">Breu descripció de l'incident pel qual s'anul·la aquesta papereta.</small>
          <textarea
            ref="reason"
            id="reason"
            v-model="reason"
            v-focus="focused === 'reason'"
            :class="{
              'form-control': true,
              'is-invalid': errors.hasOwnProperty('reason')
            }">
          </textarea>
          <div v-if="errors.hasOwnProperty('reason')" class="invalid-feedback">
            <div
              v-for="(error, key) in errors.reason"
              :key="key">
              {{ error[0] }}
            </div>
          </div>
        </div>

        <div class="form-group mb-0">
          <label class="mb-0">Signatura</label>
          <p class="mb-0"><strong>{{ user.name }} @ {{ ip }}</strong></p>
          <hr class="my-3" />
          <small class="form-text text-muted mt-0">Aquesta acció quedarà registrada a la base de dades per raons de seguretat.</small>
        </div>
      </div>
    </form>

    <div slot="modal-footer">
      <button ref="close" class="btn btn-secondary" @click="hideModal">Tanca</button>
      <button v-if="step === 1 && !success" class="btn btn-danger" @click.prevent="ballotLookup">
        <i class="far fa-search" aria-hidden="true"></i> Troba la papereta
      </button>
      <button v-if="step === 2" class="btn btn-danger" @click.prevent="annulBallot">
        <i class="far fa-ban" aria-hidden="true"></i> Anul·la la papereta
      </button>
      <button v-if="success" class="btn btn-danger" @click="success = false">
        <i class="far fa-ban" aria-hidden="true"></i> Anul·la una altra papereta
      </button>
    </div>
  </b-modal>
</template>

<script>
  import { focus } from 'vue-focus';

  export default {
    name: 'annul-ballot',

    directives: {
      focus
    },

    data () {
      return {
        step: 1,
        SID: '',
        reason: '',
        user: { name: '' },
        ip: '',
        success: false,
        errors: {},
        focused: null
      }
    },

    mounted () {
      this.user = window.app.user;
      this.ip = window.app.ip;
    },

    watch: {
      SID: function () {
        this.errors = {};
      },

      reason: function () {
        this.errors = {};
      }
    },

    methods: {
      focus (field) {
        this.focused = field;
      },

      ballotLookup () {
        Participa.annulBallot({
          SID: this.SID
        }).then(response => {
          this.step = 2;
          this.errors = {};
          this.focus('reason');
        }).catch(errors => {
          this.errors = errors;
          this.focus('SID');
        });
      },

      annulBallot () {
        Participa.annulBallot({
          SID: this.SID,
          reason: this.reason,
          confirm: true
        }).then(response => {
          this.clear();
          this.success = true;
          this.$refs.close.focus();
          Bus.$emit('refreshResults');
          Bus.$emit('refreshReports');
        }).catch(errors => {
          this.errors = errors;
          this.focus('reason');
        });
      },

      hideModal () {
        this.$refs.annulBallot.hide();
      },

      clear () {
        this.SID = '';
        this.reason = '';
        this.focused = null;
        this.step = 1;
        this.errors = {};
        this.success = false;
      },

      back () {
        this.step = 1;
        this.focus('SID');
      },
    }
  }
</script>

<style lang="scss" scoped>
  @import '../../../sass/_variables';

  .title {
    color: $red;
  }
</style>
