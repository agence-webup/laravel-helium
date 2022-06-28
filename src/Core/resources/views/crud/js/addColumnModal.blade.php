<script type="text/x-template" id="add-column-modal-template">
  <div>

    <div class="f-group">
      <label for="columnName">Nom de la colonne</label>
      <input type="text" id="columnName" v-model="columnName">
    </div>

    <div class="f-group">
      <label for="columnType">Type de donnée</label>
      <select id="columnType" v-model="columnType">
        <optgroup label="Text">
          <option value="string">string</option>
          <option value="text">text</option>
          <option value="mediumText">mediumText</option>
          <option value="longText">longText</option>
        </optgroup>
        <optgroup label="Numbers">
          <option value="integer">integer</option>
          <option value="bigInteger">bigInteger</option>
          <option value="float">float</option>
          <option value="double">double</option>
          <option value="decimal">decimal</option>
        </optgroup>
        <optgroup label="Dates">
          <option value="date">date</option>
          <option value="dateTime">dateTime</option>
        </optgroup>
        <optgroup label="Other">
          <option value="boolean">boolean</option>
          <option value="enum">enum</option>
          <option value="set">set</option>
          <option value="jsonb">jsonb</option>
          <option value="binary">binary</option>
        </optgroup>
      </select>
    </div>
    <div v-if="canChangeCollation">
      <label for="collation">Collation</label>
      <input type="text" id="collation" v-model="columnCollation">
    </div>
    <div>
      <label for="comment">Comment</label>
      <input type="text" id="comment" v-model="columnComment">
    </div>
    <div>
      <label for="default">Default</label>
      <input type="text" id="default" v-model="columnDefault">
    </div>
    <div>
      <input class="f-switch" type="checkbox" v-model="columnNullable">
      <label for="nullable">Nullable</label>
    </div>
    <div v-if="canChangeUnsigned">
      <input class="f-switch" type="checkbox" v-model="columnUnsigned">
      <label for="unsigned">Unsigned</label>
    </div>
  </div>
</script>


<script>
  Vue.component('add-column-modal', {
    data: function () {
      return {
        modal : null,
        columnName: null,
        columnType: null,
        columnLength: null,
        columnCollation:null,
        columnComment:null,
        columnDefault:null,
        columnNullable:false,
        columnUnsigned:false,
      }
    },
    mounted: function(){
        this.configureModal();
        bus.$on('openAddColumnModal', this.openModal)
    },
    computed: {
        canChangeCollation:function(){
          let allowedTypes = ["string","text","mediumText","longText"];
          return allowedTypes.includes(this.columnType);
        },
        canChangeUnsigned:function(){
          let allowedTypes = ["integer","bigInteger","float","double","decimal"];
          return allowedTypes.includes(this.columnType);
        },
      },
    methods:{
      configureModal:function(){
        // instanciate new modal
        let modal = new tingle.modal({
            footer: true,
            closeMethods: ['button', 'escape'],
            closeLabel: "Fermer"
        });
        modal.setContent(this.$el);
        modal.addFooterBtn('Annuler', 'tingle-btn tingle-btn--danger', () => {
            this.modal.close();
        });
        modal.addFooterBtn('Valider', 'tingle-btn tingle-btn--primary tingle-btn--pull-right', () => {
            this.sendData();
        });
        this.modal= modal;
      },
      openModal: function(){
        this.resetData();
        this.modal.open();
      },

      resetData: function(){
        this.columnName = null;
        this.columnType = null;
        this.columnLength = null;
        this.columnCollation = null;
        this.columnComment = null;
        this.columnDefault = null;
        this.columnNullable = true;
        this.columnUnsigned = false;
      },
      sendData: function(){
        let other = {};
        if(this.columnCollation && this.canChangeCollation){
          other['collation'] = this.columnCollation;
        }
        if(this.columnComment){
          other['comment'] = this.columnComment;
        }
        if(this.columnDefault){
          other['default'] = this.columnDefault;
        }else{
          if(this.columnNullable){
            other['default'] = "null";
          }
        }
        if(this.columnUnsigned && this.canChangeUnsigned){
          other['unsigned'] = this.columnUnsigned;
        }
        bus.$emit('onAddColumnModalSubmited',{
          'name' : this.columnName,
          'type' : this.columnType,
          'length' : this.columnLength,
          'nullable' : this.columnNullable || false,
          'other' : other,
          'removable': true
        })
        this.modal.close();
      }
    },
    template: "#add-column-modal-template"
  })
</script>
