window.collabpad = window.collabpad || {};
window.collabpad.ui = window.collabpad.ui || {};

/**
 * Dialog for initialization error
 *
 * @class
 * @extends OO.ui.ProcessDialog
 *
 * @constructor
 * @param {ve.dm.Surface} surface Surface model
 */
collabpad.ui.InvalidInitializationDialog = function ( surface ) {
	// Initialization
	this.surface = surface;

	// Parent constructor
	collabpad.ui.InvalidChangeDialog.super.call( this );
	this.$element.addClass( 'collabpad-ui-InvalidChangeDialog' );
};

/* Inheritance */
OO.inheritClass( collabpad.ui.InvalidInitializationDialog, collabpad.ui.InvalidChangeDialog );

/* Static Properties */
collabpad.ui.InvalidInitializationDialog.static.name = 'InvalidInitializationDialog';
collabpad.ui.InvalidInitializationDialog.static.title = mw.msg( 'collabpads-invalid-initialization-dialog-title' );
collabpad.ui.InvalidInitializationDialog.static.message = mw.msg( 'collabpads-invalid-initialization-dialog-message' );
collabpad.ui.InvalidInitializationDialog.static.size = 'medium';
collabpad.ui.InvalidInitializationDialog.static.actions = [
	{
		label: mw.msg( 'collabpads-invalid-change-dialog-delete-button' ),
		action: 'delete',
		flags: [ 'destructive' ]
	}
];

/* Registration */
ve.ui.windowFactory.register( collabpad.ui.InvalidInitializationDialog );
