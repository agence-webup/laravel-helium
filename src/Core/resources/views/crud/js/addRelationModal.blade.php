<script type="text/x-template" id="add-relation-modal-template">
  <div>
    <div class="f-group">
      <label for="model">Model lié</label>
      <select id="model">
        <option  v-for="(availableModel,availableModelsKey) in availableModels" value="availableModel">@{{ availableModel.name }}</option>
      </select>
    </div>
    <div class="f-group">
      <label for="columnType">Type de relation</label>
      <select id="columnType">
        <option value="onetoone">One to One</option>
        <option value="belongsto">Belongs to</option>
        <option value="onetomany">One to Many</option>
        <option value="onetomanyinverse">One To Many (Inverse)</option>
        <option value="manytomany">Many To Many</option>
      </select>
    </div>

    <div >
      {{-- <label for="collation">Collation</label>
      <input type="text" id="collation" v-model="columnCollation"> --}}
    </div>
  </div>
</script>


<script>
  Vue.component('add-relation-modal', {
    data: function () {
      return {
        modal : null,
        availableModels:@json($availableModels),

      }
    },
    mounted: function(){
        this.configureModal();
        bus.$on('openAddRelationModal', this.openModal)
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

      },
      sendData: function(){
        bus.$emit('onAddRelationModalSubmited',{
          'model' : "User",
          'type' : "belongsto",
          'column' : "user_id",
          'nullable' : true,
        })
        this.modal.close();
      }
    },
    template: "#add-relation-modal-template"
  })
</script>
