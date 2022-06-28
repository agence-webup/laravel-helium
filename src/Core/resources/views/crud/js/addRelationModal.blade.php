<script type="text/x-template" id="add-relation-modal-template">
  <div>
    <div class="f-group">
      <label for="model">Model à lier</label>
      <select id="model" v-model="selectedModel">
        <option  v-for="(availableModel,availableModelsKey) in availableModels" :value="availableModel">@{{ availableModel.nameWithoutNamespace }} (@{{ availableModel.tablename }})</option>
      </select>
    </div>

    <div class="f-group" v-if='selectedModel'>
      <label for="relationType">Type de relation</label>
      <select id="relationType" v-model="relationType" @change="updateKeys">
        <option value="hasOne">Has One</option>
        <option value="belongsTo">Belongs To</option>
        <option value="hasMany">Has Many</option>
        <option value="belongsToMany">Belongs To Many</option>
      </select>
    </div>

    <div v-if='relationType' class="mb3">
      <div class="grid grid-3">
        <h2 class="txtcenter">@{{ currentModelName }}</h2>
        <h2 class="txtcenter">@{{ relationType }}</h2>
        <h2 class="txtcenter">@{{ selectedModel.nameWithoutNamespace }}</h2>
      </div>
    </div>

    <div v-if='relationType == "hasOne"'>
      <div class="grid grid-3 mb3">
        <div class="txtcenter f-group">
          <select id="columnType" v-model="localKey">
            <option  v-for="(availableColumn,availableColumnsKey) in availableColumns" :value="availableColumn">@{{ availableColumn.name }}</option>
          </select>
        </div>
        <div class="txtcenter">
          sera référencé par
        </div>
        <div class="txtcenter f-group">
            <input type="text" v-model="foreignKey" :value='currentModelName+"_id"'>
        </div>
      </div>
      <div class="notif notif--warning mb2">
        Une modification du model @{{ selectedModel.filepath }} sera nécessaire.
      </div>
    </div>

    <div v-if='relationType == "belongsTo"'>
      <div class="grid grid-3">
        <div class="txtcenter f-group">
          <input type="text" id="collation" :value='selectedModel.nameSingular+"_id"' v-model="localKey">
        </div>
        <div class="txtcenter">
          fera référence à
        </div>
        <div class="txtcenter f-group">
          <select id="columnType" class="choices" v-model="foreignKey">
            <option  v-for="(availableColumn,availableColumnsKey) in selectedModel.columns" :value="availableColumn">@{{ availableColumn }}</option>
          </select>
        </div>
      </div>
    </div>

    <div v-if='relationType == "hasMany"'>
      <div class="grid grid-3 mb3">
        <div class="txtcenter f-group">
          <select id="columnType" v-model="localKey">
            <option v-for="(availableColumn,availableColumnsKey) in availableColumns" :value="availableColumn.name">@{{ availableColumn.name }}</option>
          </select>
        </div>
        <div class="txtcenter">
          sera référencé par
        </div>
        <div class="txtcenter f-group">
            <input type="text" :value='currentModelName+"_id"' v-model="foreignKey">
        </div>
      </div>
      <div class="notif notif--warning mb2">
        Une modification du model @{{ selectedModel.filepath }} sera nécessaire.
      </div>
    </div>

    <div v-if='relationType == "belongsToMany"'>
      <div class="notif notif--warning mb2">
        Une table pivot sera crée.
      </div>
      <div class="grid grid-3 mb3">
        <div class="txtcenter f-group">
          <select id="columnType" v-model="localKey">
            <option  v-for="(availableColumn,availableColumnsKey) in availableColumns" :value="availableColumn.name">@{{ availableColumn.name }}</option>
          </select>
        </div>
        <div class="txtcenter">
          sera lié à
        </div>
        <div class="txtcenter f-group">
          <select id="columnType" class="choices" v-model="foreignKey">
            <option  v-for="(availableColumn,availableColumnsKey) in selectedModel.columns" :value="availableColumn">@{{ availableColumn }}</option>
          </select>
        </div>
      </div>

    </div>

    <div >
      {{-- <label for="collation">Collation</label>
      <input type="text" id="collation" v-model="columnCollation"> --}}
    </div>
  </div>
</script>


<script>
  Vue.component('add-relation-modal', {
    props: [
      'currentModelName',
      'availableColumns'
    ],
    data: function () {
      return {
        modal : null,
        selectedModel:null,
        relationType:null,
        localKey:null,
        foreignKey:null,
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
        this.selectedModel = null;
        this.relationType = null;
      },
      updateKeys:function(){
        switch (this.relationType) {
          case "hasOne":
            this.localKey = this.availableColumns[0];
            this.foreignKey = this.currentModelName+"_id";
            break;
          case "belongsTo":
            this.localKey = this.selectedModel.nameSingular+"_id";
            this.foreignKey = this.selectedModel.columns[0];
            break;
          case "hasMany":
            this.localKey = this.availableColumns[0];
            this.foreignKey = this.currentModelName+"_id";
            break;
          case "belongsToMany":
            this.localKey = ""
            this.foreignKey = ""
            break;
        }
      },
      sendData: function(){
        bus.$emit('onAddRelationModalSubmited',{
          'model' : this.selectedModel.nameWithoutNamespace,
          'type' : this.relationType,
          'localKey' : this.localKey,
          'foreignKey' : this.foreignKey,
          'nullable' : true,
        })
        this.modal.close();
      }
    },
    template: "#add-relation-modal-template"
  })
</script>
