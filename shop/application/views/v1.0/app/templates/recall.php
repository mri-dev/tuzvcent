<md-dialog aria-label="Ingyenes visszahívás kérése" class="ajaxdialog recall">
  <form ng-cloak>
    <md-toolbar>
      <div class="md-toolbar-tools">
        <h2>Ingyenes visszahívás kérése</h2>
        <span flex></span>
        <md-button class="md-icon-button" ng-click="closeDialog()">
          <md-icon md-svg-src="img/icons/ic_close_24px.svg" aria-label="Close dialog"></md-icon>
        </md-button>
      </div>
    </md-toolbar>

    <md-dialog-content>
      <div class="md-dialog-content">
        <md-input-container class="md-icon-float md-block">
          <label>Az Ön teljes neve</label>
          <md-icon md-svg-src="<?=IMG?>icons/ic_person_orange_24px.svg"></md-icon>
          <input required ng-model="recall.name">
        </md-input-container>
        <md-input-container class="md-icon-float md-block">
          <label>Az Ön telefonszáma</label>
          <md-icon md-svg-src="<?=IMG?>/icons/ic_person_24px.svg"></md-icon>
          <input required ng-model="recall.phone" ng-pattern="/^\+36 [0-9]{1,3} [0-9]{6,10}$/">
          <div class="hint" ng-show="showHints">Minta: +36 1 123456</div>
          <div ng-messages="recall.phone.$error" multiple md-auto-hide="false">
            <div ng-message="required">
              Telefonszám kitöltése kötelező!
            </div>
            <div ng-message="pattern">
              Nem megfelelő a telefonszám formátuma.
            </div>
          </div>
        </md-input-container>
        <br>
        <md-input-container class="md-block">
          <label>Milyen ügyben keres minket?</label>
          <textarea ng-model="recall.subject" md-maxlength="200" rows="3" md-select-on-focus></textarea>
        </md-input-container>
      </div>
    </md-dialog-content>

    <md-dialog-actions layout="row">
      <md-button ng-click="closeDialog()">
       Mégse
      </md-button>
      <span flex></span>
      <md-button ng-click="answer('useful')">
        Visszahívás kérése
      </md-button>
    </md-dialog-actions>
  </form>
</md-dialog>
