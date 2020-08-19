<script>
  new Vue({
    el: '#app',
    data: {
      enableId: "{{ old('enableId',true) }}",
      enableSoftDelete: "{{ old('enableSoftDelete',true) }}",
      enableTimestamps: "{{ old('enableTimestamps',true) }}",
      customColumns:@json($oldCustomColumns),
      customRelations:@json($oldCustomRelations)
    },
    filters: {
      json: (value) => { return JSON.stringify(value) }
    },
    mounted: function(){
      //Contextuel helium box actions are removed by vuejs, so need to recreate dropmic
      [].forEach.call(document.querySelectorAll("[data-dropmic]"), function(e) {
          new Dropmic(e)
      })
      bus.$on('onAddColumnModalSubmited', this.addColumn)
      bus.$on('onAddRelationModalSubmited', this.addRelation)
    },
    methods: {
      openAddColumnModal:function(){
        bus.$emit('openAddColumnModal')
      },
      openAddRelationModal:function(){
        bus.$emit('openAddRelationModal')
      },
      addColumn:function(data){
        this.customColumns.push(data);
      },
      addRelation:function(data){
        this.customRelations.push(data);
      },
      removeColumnByName: function(columnName){
        const result = this.customColumns.filter(customColumn => {
          if(customColumn.name == columnName && customColumn.removable == true){
            return false;
          }
          return true;
        });
        this.customColumns=result;
      },
      submitForm:function(e){
        console.log("Pouet");
        return true;
      }
    },
    computed: {
      formData: function(){

      },
      fullColumns: function(){
        let columns = [];
        if(this.enableId){
          columns.push({
            'name' : "id",
            'type' : "-",
            'length' : "-",
            'nullable' : false,
            'other' : {
              "autoincrement" : true,
            },
            'removable': false
          });
        }
        this.customRelations.forEach(element => {
          if(element.column){
            columns.push({
            'name' : element.column,
            'type' : element.relationType,
            'length' : "-",
            'nullable' : true,
            'other' : {},
            'removable': false
          });
          }
        });
        columns = columns.concat(this.customColumns);
        if(this.enableTimestamps){
          columns.push({
            'name' : "created_at",
            'type' : "-",
            'length' : "-",
            'nullable' : true,
            'other' : {},
            'removable': false
          });
          columns.push({
            'name' : "updated_at",
            'type' : "-",
            'length' : "-",
            'nullable' : true,
            'other' : {},
            'removable': false
          });
        }
        if(this.enableSoftDelete){
          columns.push({
            'name' : "deleted_at",
            'type' : "-",
            'length' : "-",
            'nullable' : true,
            'other' : {},
            'removable': false
          });
        }

        return columns;
      }
    }
  })
</script>
