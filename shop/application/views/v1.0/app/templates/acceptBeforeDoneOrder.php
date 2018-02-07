<md-dialog aria-label="Tűzvédő anyagok vásárlásának speciális feltételei." class="ajaxdialog accept-order">
  <form ng-cloak>
    <md-toolbar>
      <div class="md-toolbar-tools">
        <h2>Tűzvédő anyagok vásárlásának speciális feltételei.</h2>
        <span flex></span>
        <md-button class="md-icon-button" ng-click="closeDialog()">
          <md-icon md-svg-src="/src/images/ic_close_white_24px.svg" aria-label="Close dialog"></md-icon>
        </md-button>
      </div>
    </md-toolbar>

    <md-dialog-content>
      <div class="md-dialog-content">
        ASD
      </div>
    </md-dialog-content>

    <md-dialog-actions layout="row">
      <md-button ng-click="closeDialog()">
       Mégse
      </md-button>
      <span flex></span>
      <md-button class="md-warn md-raised" ng-click="acceptOrder()">
        Elfogadom a feltételeket
      </md-button>
    </md-dialog-actions>
  </form>
</md-dialog>
