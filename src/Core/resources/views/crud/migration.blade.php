@php
Helium::header()->title("Création d'un model et de sa migration");
Helium::header()->save("Sauvegarder","createMigration");
@endphp

@extends('helium::layouts.master')

@section('content')
<div id="app">
  <form id="createMigration" @submit.prevent="submitForm" class="" action="{{ route('crud.migration.post') }}"
    method="post">
    {{ csrf_field() }}

    <x-helium-box>
      <x-slot name="header">
        <x-helium-box-header title="Configuration" />
      </x-slot>

      <div>
        {!! Form::create('text', 'name')
        ->label('Nom de la table')
        !!}

        <p>Champs particuliers</p>
        <div class="f-group">
          <div>
            <input class="f-switch" type="checkbox" v-model="enableId" name="enableId">
            <label for="migration">Id</label>
          </div>
          <div>
            <input class="f-switch" type="checkbox" v-model="enableSoftDelete" name="enableSoftDelete">
            <label for="migration">Soft delete</label>
          </div>
          <div>
            <input class="f-switch" type="checkbox" v-model="enableTimestamps" name="enableTimestamps">
            <label for="migration">Timestamps (created_at & updated_at)</label>
          </div>
        </div>
      </div>
    </x-helium-box>



    {{-- <x-helium-box>
      <x-slot name="header">
        <x-helium-box-header title="Relations" :actions="[
        'Ajouter une relation' => [
            '@click.prevent' => 'openAddRelationModal',
        ]]" />
      </x-slot>
      <table>
        <thead>
          <tr>
            <th class="txtleft">Model</th>
            <th class="txtleft">Type</th>
            <th class="txtcenter table-150p">Colonne</th>
            <th class="txtcenter table-100p">Nullable</th>
            <th class="table-actions"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(relation,relationKey) in customRelations">
            <th class="txtleft">@{{ relation.model }}</th>
    <th class="txtcenter">@{{ relation.type }}</th>
    <th class="txtcenter">@{{ relation.column }}</th>
    <th class="txtcenter">
      <span v-if="relation.nullable" class="tag tag--green">oui</span>
      <span v-else class="tag tag--red">non</span>
    </th>
    <th>
      <button @click.prevent='removeRelationByName(relation.name)'> X </button>
    </th>
    </tr>
    </tbody>
    </table>

    <add-relation-modal :available-columns="fullColumns" :current-model-name="'todo'"></add-relation-modal>

    <input type="hidden" name="customRelations" :value='customRelations | json'>

    </x-helium-box> --}}


    <x-helium-box>
      <x-slot name="header">
        <x-helium-box-header title="Migration" :actions="[
        'Ajouter une colonne' => [
            '@click.prevent' => 'openAddColumnModal',
        ]]" />
      </x-slot>
      <table>
        <thead>
          <tr>
            <th class="txtleft">Nom</th>
            <th class="txtcenter table-150p">Type</th>
            <th class="txtcenter table-100p">Length</th>
            <th class="txtcenter table-100p">Nullable</th>
            <th class="txtleft">Other</th>
            <th class="table-actions"></th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(column,columnKey) in fullColumns">
            <th class="txtleft">@{{ column.name }}</th>
            <th class="txtcenter">@{{ column.type }}</th>
            <th class="txtcenter">@{{ column.length }}</th>
            <th class="txtcenter">
              <span v-if="column.nullable" class="tag tag--green">oui</span>
              <span v-else class="tag tag--red">non</span>
            </th>
            <th class="txtleft">
              <div v-if="Object.keys(column.other).length > 0">
                <span v-for="(value,label) in column.other">@{{ label }} : @{{ value || "-" }}<br /></span>
              </div>
              <span v-else>-</span>
            </th>
            <th>
              <button v-if="column.removable" @click.prevent='removeColumnByName(column.name)'> X </button>
            </th>
          </tr>
        </tbody>
      </table>

      <add-column-modal></add-column-modal>
      <input type="hidden" name="customColumns" :value='customColumns | json'>
    </x-helium-box>
  </form>
</div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.11/vue.min.js"></script>
<script>
  const bus = new Vue();
</script>
@include("helium::crud.js.addRelationModal")
@include("helium::crud.js.addColumnModal")
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

@endsection
