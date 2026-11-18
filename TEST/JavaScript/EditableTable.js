/**
 * EditableTable -- a small, dependency-free, editable data grid for ReFringe.
 *
 * Written as an in-house replacement for the third-party "Handsontable" grid,
 * so that ReFringe pages can be used without a commercial license. It keeps the
 * subset of the Handsontable API that ReFringe pages rely upon, so that pages can
 * switch over with minimal changes:
 *
 *      var table = new EditableTable(containerElement, {
 *          data: [[false, 7, 'Comedy'], ...],   //Array of row arrays.
 *          rowHeaders: true,                    //Show 1-based row numbers.
 *          colHeaders: ['Include?', '', 'Name'],
 *          hiddenColumns: { columns: [1] },     //Hidden, but still in the data.
 *          width: 450,                          //Pixels (or any CSS width string).
 *          height: 300,                         //Optional; pixels. Scrolls if exceeded.
 *          columnSorting: true,                 //Click a header: asc -> desc -> unsorted.
 *          stretchH: 'all',                     //'all' = widen every column, in proportion,
 *                                               //  to fill "width"; 'none' = use the widths as given.
 *          readOnly: false,                     //Table-wide; can be overridden per column.
 *          autoWrapRow: true,                   //Left/Right arrows wrap across rows.
 *          autoWrapCol: true,                   //Up/Down arrows wrap across columns.
 *          columns: [
 *              { type: 'checkbox', width: 55, className: 'et-center' },
 *              { type: 'text' },
 *              { type: 'text', readOnly: true },
 *              { type: 'html' },                //Shows basic formatting; see below.
 *              { type: 'text', renderer: myRenderer }
 *          ]
 *      });
 *
 *      table.countRows();             table.countCols();
 *      table.getDataAtCell(row, col); table.getDataAtRow(row);  table.getData();
 *      table.getSourceData();         //Copies of the rows as loaded (arrays or objects).
 *      table.setDataAtCell(row, col, value);
 *      table.setDataAtRowProp(row, 'propName', value);    //Object rows; see below.
 *      table.addHook('afterOnCellMouseUp', function (event, coords, td) { ... });
 *      table.loadData(aryData);       table.updateSettings({ height: 200 });
 *      table.getCell(row, col);       table.scrollViewportTo(row, col);
 *      table.render();                table.destroy();
 *
 * Index conventions (same as Handsontable):
 *   - "row" arguments are VISUAL indexes, i.e. the order currently shown on
 *     screen (which changes when the user sorts by a column).
 *   - "col" arguments include hidden columns: hiding a column only stops it
 *     from being drawn, so getDataAtCell(row, 1) still returns a hidden ID.
 *   - All getters return copies; changing them does not change the table.
 *
 * Editing (mouse and keyboard):
 *   - Click a cell to select it; arrow keys / Tab / Shift+Tab move the selection.
 *   - Text cells: double-click, Enter or F2 opens the editor; typing a character
 *     starts a new value. In the editor, Enter (or Tab) saves and moves on,
 *     Escape cancels, and clicking elsewhere saves.
 *   - Delete / Backspace clears a selected text cell (or unchecks a checkbox).
 *   - Checkbox cells: click the box, or press Space / Enter while selected.
 *   - Values typed into text cells are stored as strings; checkbox values are
 *     stored as the booleans true / false.
 *
 * Rows as objects: rows may be plain objects instead of arrays. Each column's
 * "data" option names the property it shows, e.g.
 *
 *      data: [{ selected: false, ID: 7, name: 'Main Hall' }, ...],
 *      columns: [{ data: 'selected', type: 'checkbox' }, { data: 'ID' }, { data: 'name' }]
 *
 * (Without "columns", the first row's property order is used.) getDataAtRow()
 * and getData() still return arrays of values in column order, while
 * getSourceData() returns copies of the objects themselves.
 *
 * Per-cell settings: a "cells" function can override a single cell's
 * "readOnly", "className", "type" or "renderer":
 *
 *      cells: function (row, col, prop) { return (row === 0 && col === 0) ? { readOnly: true } : {}; }
 *
 * As in Handsontable, "row" here is the row's position in the data as it was
 * loaded, so the setting stays with the same row when the table is sorted.
 *
 * Mouse hooks: "afterOnCellMouseDown" and "afterOnCellMouseUp" may be given as
 * settings, or added with addHook(name, fn). They are called as
 * fn.call(table, event, { row: visualRow, col: col }, td) for clicks on body
 * cells (not on headers).
 *
 * Column types:
 *   - 'text' (the default, also used for any other type name): plain text.
 *   - 'checkbox': the value is true or false.
 *   - 'html': the value is treated as HTML, but only basic formatting tags are
 *     kept (such as <b>, <i>, <u>, <br>, <p>, lists); every attribute, and any
 *     other tag, is removed, so nothing in the data can run scripts. Entities
 *     such as "&amp;" are shown as "&". Editing shows the underlying HTML.
 *
 * Custom renderers: a column's (or a cell's) "renderer" option takes a function
 * with the same arguments as a Handsontable renderer:
 *
 *      function (instance, td, row, col, prop, value, cellProperties) { ... }
 *
 * The <td> arrives empty; the function fills it. Use textContent, or
 * EditableTable.setSafeHtml(element, html), never innerHTML with data.
 * Sorting moves rows without re-running renderers, so a renderer's output
 * should depend on the cell's value, not on the row's position.
 * EditableTable.highlightText(element, text, className) wraps matches of
 * "text" in <span class="className">.
 *
 * Copy (Ctrl+C) copies the selected cell's value; paste (Ctrl+V) replaces it.
 *
 * Security (what this file does, and what it cannot do):
 *   - Nothing in the data is ever run as code: text cells and headers are
 *     shown with textContent, 'html' cells go through setSafeHtml() (see above).
 *   - Text typed or pasted into a cell is cleaned (see cleanUserText()): NUL
 *     and other invisible control characters are removed (tab and line
 *     breaks are kept), and broken UTF-16 is repaired. Otherwise it is kept
 *     exactly as typed, quotes and all.
 *   - Row indexes and column indexes must be whole numbers (anything else is
 *     treated as out of range), and the property names "__proto__",
 *     "constructor" and "prototype" are never read or written, so data and
 *     settings (for example, rows parsed from JSON) cannot tamper with the
 *     table's objects.
 *   - Read-only cells cannot be changed through the grid's own user interface.
 *   - THIS DOES NOT PROTECT THE DATABASE. Everything here runs in the user's
 *     browser, where it can be changed or skipped (e.g. with the browser's
 *     developer tools, or by posting to the server directly). The server must
 *     treat every value it receives as untrusted: use PDO prepared statements
 *     with bound parameters (never build SQL by joining strings), check that
 *     IDs are integers, and check that the user may change that record.
 *
 * Styling lives in /TEST/CSS/EditableTable.css. Add the class "et-theme-dark" to
 * the container for the dark look.
 */
(function (global) {
    'use strict';

    var DEFAULT_SETTINGS = {
        data: [],
        rowHeaders: false,
        colHeaders: false,
        hiddenColumns: null,
        width: null,
        height: null,
        columnSorting: false,
        stretchH: 'none',
        readOnly: false,
        autoWrapRow: false,
        autoWrapCol: false,
        columns: null,
        cells: null,
        afterOnCellMouseDown: null,
        afterOnCellMouseUp: null
    };

    var DEFAULT_COLUMN_WIDTH = 100;    //Pixels, for columns without a numeric "width".
    var ROW_HEADER_WIDTH = 50;

    /**   Converts a number to a pixel string; passes other strings through.
     *
     * @param {number|string|null} value
     * @returns {string} -- A CSS length, or '' for "no value".
     */
    function toCssLength(value) {
        if (value === null || value === undefined || value === '') {
            return '';
        }
        return (typeof value === 'number') ? value + 'px' : String(value);
    }

    /**   Compares two cell values for sorting. Empty values (null, undefined,
     * '') are handled by the caller, so they always sort last.
     */
    function compareValues(a, b) {
        if (typeof a === 'boolean' && typeof b === 'boolean') {
            return (a === b) ? 0 : (a ? 1 : -1);    //false before true.
        }
        if (typeof a === 'number' && typeof b === 'number') {
            return a - b;
        }
        return String(a).localeCompare(String(b), undefined, { numeric: true, sensitivity: 'base' });
    }

    function isEmptyValue(value) {
        return value === null || value === undefined || value === '';
    }

    //Property names that could reach (or replace) an object's prototype. They
    //are never used as row properties, column "data" names or setting names.
    var UNSAFE_KEYS = new Set(['__proto__', 'constructor', 'prototype']);

    /** @returns {boolean} -- Whether "key" is a harmless property name. */
    function isSafeKey(key) {
        return (typeof key === 'string' || typeof key === 'number') && !UNSAFE_KEYS.has(String(key));
    }

    /** @returns {boolean} -- Whether "value" is a whole number >= 0. */
    function isIndex(value) {
        return Number.isInteger(value) && value >= 0;
    }

    /**   Copies the object's own enumerable properties into "target", except
     * the unsafe ones. (Unlike Object.assign(), a "__proto__" property that
     * came from JSON.parse() cannot replace the target's prototype.)
     *
     * @returns {Object} -- "target".
     */
    function assignSafe(target, source) {
        if (source !== null && typeof source === 'object') {
            Object.keys(source).forEach(function (key) {
                if (isSafeKey(key)) {
                    target[key] = source[key];
                }
            });
        }
        return target;
    }

    /**   Copies a row: arrays element by element, objects property by
     * property (a "shallow" copy, without any unsafe property names).
     * Anything else becomes an empty row.
     */
    function copyRow(row) {
        if (Array.isArray(row)) {
            return row.slice();
        }
        return (row !== null && typeof row === 'object') ? assignSafe({}, row) : [];
    }

    //C0 control characters other than tab (\x09), line feed (\x0A) and
    //carriage return (\x0D), plus DEL. These are invisible, can hide what a
    //value really contains, and NUL is rejected by PostgreSQL text columns.
    var CONTROL_CHARACTERS = /[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g;

    /**   Cleans text that the user typed or pasted into a cell: removes the
     * control characters above, and replaces broken UTF-16 (which cannot be
     * sent to the server as valid UTF-8) with U+FFFD. Everything else, such
     * as quotes, "<" and ";", is kept: making it safe is the job of whatever
     * later shows it (this file, for display) or stores it (the server, with
     * bound parameters).
     *
     * @param {string} text
     * @returns {string}
     */
    function cleanUserText(text) {
        var cleaned = String(text).replace(CONTROL_CHARACTERS, '');
        if (typeof cleaned.toWellFormed === 'function') {
            return cleaned.toWellFormed();
        }
        //For older browsers: the same as toWellFormed().
        var result = '';
        for (var i = 0; i < cleaned.length; i++) {
            var code = cleaned.charCodeAt(i);
            if (code >= 0xD800 && code <= 0xDBFF && i + 1 < cleaned.length) {
                var next = cleaned.charCodeAt(i + 1);
                if (next >= 0xDC00 && next <= 0xDFFF) {
                    result += cleaned.charAt(i) + cleaned.charAt(i + 1);    //A valid pair.
                    i++;
                    continue;
                }
            }
            result += (code >= 0xD800 && code <= 0xDFFF) ? '\uFFFD' : cleaned.charAt(i);
        }
        return result;
    }

    class EditableTable {
        /**
         * @param {HTMLElement} container -- The element that will hold the table.
         * @param {Object} settings -- See the description at the top of this file.
         */
        constructor(container, settings) {
            if (!(container instanceof HTMLElement)) {
                throw new TypeError('EditableTable: "container" must be a DOM element.');
            }
            this.container = container;
            this.settings = Object.assign({}, DEFAULT_SETTINGS);
            this._data = [];        //Rows in the order they were loaded ("physical" order).
            this._order = [];       //_order[visualRow] = physical row index.
            this._sort = null;      //{ col: <number>, direction: 'asc' | 'desc' } or null.
            this._selected = null;  //{ row: <visual row>, col: <column> } or null.
            this._editor = null;    //State of the open text editor, or null.
            this._hooks = new Map(); //Hook name -> array of functions added with addHook().
            this._sourceKeys = null; //Property names, for object rows without "columns".

            this._buildShell();

            settings = assignSafe({}, settings);
            var initialData = ('data' in settings) ? settings.data : [];
            delete settings.data;
            assignSafe(this.settings, settings);
            this.loadData(initialData);    //Also renders.
        }

        // ------------------------------------------------------------------
        //  Public API
        // ------------------------------------------------------------------

        /** @returns {number} -- The number of data rows. */
        countRows() {
            return this._data.length;
        }

        /** @returns {number} -- The number of columns, hidden ones included. */
        countCols() {
            if (Array.isArray(this.settings.columns)) {
                return this.settings.columns.length;
            }
            if (Array.isArray(this.settings.colHeaders)) {
                return this.settings.colHeaders.length;
            }
            if (this._sourceKeys) {
                return this._sourceKeys.length;
            }
            return this._data.reduce(function (max, row) {
                return Math.max(max, Array.isArray(row) ? row.length : 0);
            }, 0);
        }

        /**   Returns the value of one cell, or null when out of range.
         *
         * @param {number} row -- Visual row index.
         * @param {number} col -- Column index (hidden columns count).
         */
        getDataAtCell(row, col) {
            var physicalRow = this._toPhysicalRow(row);
            if (physicalRow === null || !this._isColumn(col)) {
                return null;
            }
            return this._valueAt(physicalRow, col);
        }

        /**   Returns a copy of one row (all columns, hidden ones included),
         * or null when out of range.
         *
         * @param {number} row -- Visual row index.
         */
        getDataAtRow(row) {
            var physicalRow = this._toPhysicalRow(row);
            if (physicalRow === null) {
                return null;
            }
            return this._rowValues(physicalRow);
        }

        /** @returns {Array<Array>} -- A copy of all rows, in visual order. */
        getData() {
            var self = this;
            return this._order.map(function (physicalRow) {
                return self._rowValues(physicalRow);
            });
        }

        /**   Returns copies of all rows exactly as they were loaded (arrays or
         * objects, including any properties that no column shows), in the
         * order they were loaded, with any edits applied.
         *
         * @returns {Array<Array|Object>}
         */
        getSourceData() {
            return this._data.map(copyRow);
        }

        /**   Sets the value of one cell and redraws it.
         *
         * @param {number} row -- Visual row index.
         * @param {number} col -- Column index (hidden columns count).
         * @param {*} value
         */
        setDataAtCell(row, col, value) {
            if (Array.isArray(row)) {    //A list of [row, col, value] changes.
                row.forEach(function (change) {
                    this.setDataAtCell(change[0], change[1], change[2]);
                }, this);
                return;
            }
            var physicalRow = this._toPhysicalRow(row);
            if (physicalRow === null || !this._isColumn(col)) {
                return;
            }
            this._setValueAt(physicalRow, col, value);
            var td = this._findCell(row, col);
            if (td) {
                this._fillCell(td, row, col);
            }
        }

        /**   Sets a named property of an object row and redraws any cell
         * showing it. Also accepts a list of [row, prop, value] changes.
         *
         * @param {number|Array} row -- Visual row index, or a list of changes.
         * @param {string} prop -- The property name (a column's "data").
         * @param {*} value
         */
        setDataAtRowProp(row, prop, value) {
            if (Array.isArray(row)) {
                row.forEach(function (change) {
                    this.setDataAtRowProp(change[0], change[1], change[2]);
                }, this);
                return;
            }
            var physicalRow = this._toPhysicalRow(row);
            if (physicalRow === null || !isSafeKey(prop) || prop === '') {
                return;    //Unsafe names such as "__proto__" are ignored.
            }
            this._data[physicalRow][prop] = value;
            for (var col = 0; col < this.countCols(); col++) {
                if (this._keyFor(col) === prop) {
                    var td = this._findCell(row, col);
                    if (td) {
                        this._fillCell(td, row, col);
                    }
                }
            }
        }

        /**   Adds a function to be called on a mouse hook (see the top of this
         * file): 'afterOnCellMouseDown' or 'afterOnCellMouseUp'.
         */
        addHook(name, fn) {
            if (typeof fn !== 'function') {
                return;
            }
            if (!this._hooks.has(name)) {
                this._hooks.set(name, []);
            }
            this._hooks.get(name).push(fn);
        }

        /**   Removes a function added with addHook(). */
        removeHook(name, fn) {
            var list = this._hooks.get(name) || [];
            var index = list.indexOf(fn);
            if (index !== -1) {
                list.splice(index, 1);
            }
        }

        /**   Replaces all of the table's data. Any sorting, selection and open
         * editor are reset. The rows passed in are copied, so the caller's
         * array is never modified by later edits.
         *
         * @param {Array<Array|Object>} data -- An array of row arrays, or of
         *      row objects (see "Rows as objects" at the top of this file).
         */
        loadData(data) {
            if (data !== null && data !== undefined && !Array.isArray(data)) {
                throw new TypeError('EditableTable: "data" must be an array of rows.');
            }
            this._closeEditor(false, false);
            this._data = (data || []).map(copyRow);
            var first = this._data[0];
            this._sourceKeys = (first && !Array.isArray(first)) ? Object.keys(first) : null;
            this._order = this._data.map(function (row, index) {
                return index;
            });
            this._sort = null;
            this._selected = null;
            this.render();
        }

        /**   Merges new settings into the table and redraws it. Passing a
         * "data" property is the same as calling loadData().
         *
         * @param {Object} settings
         */
        updateSettings(settings) {
            settings = assignSafe({}, settings);
            var hasData = ('data' in settings);
            var newData = settings.data;
            delete settings.data;
            assignSafe(this.settings, settings);
            if (hasData) {
                this.loadData(newData);
            } else {
                this.render();
            }
        }

        /**   Returns the <td> of a cell, or null if it is hidden or out of range.
         *
         * @param {number} row -- Visual row index.
         * @param {number} col -- Column index (hidden columns count).
         */
        getCell(row, col) {
            return this._findCell(row, col);
        }

        /**   Scrolls the table (not the page) just enough for a cell to be
         * fully visible, below the column headers and right of the row numbers.
         *
         * @param {number} row -- Visual row index.
         * @param {number} col -- Column index (hidden columns count).
         * @returns {boolean} -- false if the cell does not exist or is hidden.
         */
        scrollViewportTo(row, col) {
            var td = this._findCell(row, col);
            if (!td) {
                return false;
            }
            var wrapper = this._wrapper;
            var view = wrapper.getBoundingClientRect();
            var cell = td.getBoundingClientRect();
            var head = this._table.tHead;
            var top = view.top + wrapper.clientTop + (head ? head.offsetHeight : 0);
            var bottom = view.top + wrapper.clientTop + wrapper.clientHeight;
            var left = view.left + wrapper.clientLeft + (this.settings.rowHeaders ? ROW_HEADER_WIDTH : 0);
            var right = view.left + wrapper.clientLeft + wrapper.clientWidth;

            if (cell.top < top) {
                wrapper.scrollTop -= (top - cell.top);
            } else if (cell.bottom > bottom) {
                wrapper.scrollTop += Math.min(cell.bottom - bottom, cell.top - top);
            }
            if (cell.left < left) {
                wrapper.scrollLeft -= (left - cell.left);
            } else if (cell.right > right) {
                wrapper.scrollLeft += Math.min(cell.right - right, cell.left - left);
            }
            return true;
        }

        /**   Redraws the whole table from its data and settings. */
        render() {
            this._closeEditor(false, false);
            this._applyShellStyles();

            //Measure the space available *before* the new table is inserted:
            //measuring afterwards would force the browser to lay out the
            //whole new table immediately, and then again once it is sized.
            var availableWidth = this._wrapper.clientWidth;

            var table = document.createElement('table');
            table.className = 'et-table';
            table.setAttribute('role', 'grid');
            table.appendChild(this._buildColGroup());
            if (this.settings.colHeaders) {
                table.appendChild(this._buildHead());
            }
            table.appendChild(this._buildBody());

            this._table = table;
            this._applyColumnWidths(availableWidth);
            this._wrapper.replaceChildren(table);
            this._showSelection();
        }

        /**   Removes the table from the page and restores the container. */
        destroy() {
            this._closeEditor(false, false);
            if (this._resizeObserver) {
                this._resizeObserver.disconnect();
                this._resizeObserver = null;
            }
            this.container.replaceChildren();
            this.container.classList.remove('et-container');
            this._wrapper = null;
            this._table = null;
            this._data = [];
            this._order = [];
        }

        // ------------------------------------------------------------------
        //  Building the DOM
        // ------------------------------------------------------------------

        _buildShell() {
            this.container.classList.add('et-container');
            var wrapper = document.createElement('div');
            wrapper.className = 'et-wrapper';
            wrapper.tabIndex = 0;    //So the grid can receive keyboard input.
            this.container.replaceChildren(wrapper);
            this._wrapper = wrapper;

            wrapper.addEventListener('mousedown', this._onMouseDown.bind(this));
            wrapper.addEventListener('mouseup', this._onMouseUp.bind(this));
            wrapper.addEventListener('dblclick', this._onDoubleClick.bind(this));
            wrapper.addEventListener('click', this._onClick.bind(this));
            wrapper.addEventListener('change', this._onChange.bind(this));
            wrapper.addEventListener('keydown', this._onKeyDown.bind(this));
            wrapper.addEventListener('focusout', this._onFocusOut.bind(this));
            wrapper.addEventListener('copy', this._onCopy.bind(this));
            wrapper.addEventListener('paste', this._onPaste.bind(this));

            //Stretched columns depend on the wrapper's width (which also
            //changes when a vertical scrollbar appears), so re-fit on resize.
            this._resizeObserver = null;
            if (typeof ResizeObserver === 'function') {
                this._resizeObserver = new ResizeObserver(function () {
                    this._applyColumnWidths();
                }.bind(this));
                this._resizeObserver.observe(wrapper);
            }
        }

        _applyShellStyles() {
            var wrapper = this._wrapper;
            wrapper.style.width = toCssLength(this.settings.width);
            wrapper.style.height = toCssLength(this.settings.height);
            wrapper.classList.toggle('et-fixed-height', !isEmptyValue(this.settings.height));
        }

        _buildColGroup() {
            var colGroup = document.createElement('colgroup');
            if (this.settings.rowHeaders) {
                var headerCol = document.createElement('col');
                headerCol.className = 'et-row-header-col';
                colGroup.appendChild(headerCol);
            }
            this._visibleColumns().forEach(function (col) {
                var colElement = document.createElement('col');
                colElement.dataset.col = col;
                colGroup.appendChild(colElement);
            });
            return colGroup;
        }

        /**   A column's configured width in pixels (the default when unset). */
        _baseColumnWidth(col) {
            var width = this._columnSetting(col, 'width');
            return (typeof width === 'number' && width > 0) ? width : DEFAULT_COLUMN_WIDTH;
        }

        /**   Sets the pixel width of every column. With stretchH: 'all', if
         * there is spare room, the configured widths are scaled up in proportion
         * so that the columns exactly fill the wrapper. If the columns are
         * already wider than the wrapper, they keep their widths and the table
         * scrolls sideways.
         */
        _applyColumnWidths(availableWidth) {
            if (!this._table || !this._wrapper) {
                return;
            }
            if (typeof availableWidth !== 'number') {
                availableWidth = this._wrapper.clientWidth;
            }
            var self = this;
            var columns = this._visibleColumns();
            var rowHeaderWidth = this.settings.rowHeaders ? ROW_HEADER_WIDTH : 0;
            var widths = columns.map(function (col) {
                return self._baseColumnWidth(col);
            });
            var baseTotal = widths.reduce(function (sum, width) {
                return sum + width;
            }, 0);

            var available = availableWidth - rowHeaderWidth;
            if (this.settings.stretchH === 'all' && baseTotal > 0 && available > baseTotal) {
                var used = 0;
                widths = widths.map(function (width, index) {
                    if (index === widths.length - 1) {
                        return available - used;    //The last column absorbs rounding.
                    }
                    var stretched = Math.floor(width * available / baseTotal);
                    used += stretched;
                    return stretched;
                });
            }

            var colElements = this._table.querySelectorAll('col');
            var offset = 0;
            if (this.settings.rowHeaders) {
                colElements[0].style.width = rowHeaderWidth + 'px';
                offset = 1;
            }
            var total = rowHeaderWidth;
            widths.forEach(function (width, index) {
                colElements[index + offset].style.width = width + 'px';
                total += width;
            });
            this._table.style.width = total + 'px';
        }

        _buildHead() {
            var thead = document.createElement('thead');
            var tr = document.createElement('tr');
            if (this.settings.rowHeaders) {
                var corner = document.createElement('th');
                corner.className = 'et-corner';
                tr.appendChild(corner);
            }
            var self = this;
            this._visibleColumns().forEach(function (col) {
                var th = document.createElement('th');
                th.className = 'et-col-header';
                th.scope = 'col';
                th.dataset.col = col;

                var label = document.createElement('span');
                label.className = 'et-header-label';
                label.textContent = self._headerText(col);
                th.appendChild(label);

                if (self.settings.columnSorting) {
                    th.classList.add('et-sortable');
                    th.title = 'Click to sort';
                    var direction = (self._sort && self._sort.col === col) ? self._sort.direction : null;
                    th.setAttribute('aria-sort',
                        direction === 'asc' ? 'ascending' : (direction === 'desc' ? 'descending' : 'none'));
                    if (direction) {
                        th.classList.add('et-sorted-' + direction);
                    }
                }
                tr.appendChild(th);
            });
            thead.appendChild(tr);
            return thead;
        }

        _buildBody() {
            var tbody = document.createElement('tbody');
            var visibleColumns = this._visibleColumns();
            this._rowElements = [];    //_rowElements[physical row] = its <tr>.
            for (var row = 0; row < this._order.length; row++) {
                var tr = document.createElement('tr');
                this._rowElements[this._order[row]] = tr;
                if (this.settings.rowHeaders) {
                    var th = document.createElement('th');
                    th.className = 'et-row-header';
                    th.scope = 'row';
                    th.textContent = String(row + 1);
                    tr.appendChild(th);
                }
                for (var i = 0; i < visibleColumns.length; i++) {
                    var td = document.createElement('td');
                    td.dataset.row = row;
                    td.dataset.col = visibleColumns[i];
                    this._fillCell(td, row, visibleColumns[i]);
                    tr.appendChild(td);
                }
                tbody.appendChild(tr);
            }
            return tbody;
        }

        /**   Draws a cell's value, and its classes, into its <td>. */
        _fillCell(td, row, col) {
            var value = this.getDataAtCell(row, col);
            var meta = this._metaAt(row, col);

            td.className = 'et-cell';
            if (typeof meta.className === 'string' && meta.className !== '') {
                td.className += ' ' + meta.className;
            }
            if (meta.readOnly) {
                td.classList.add('et-readonly');
            }

            var type = meta.type;
            if (type === 'html') {
                td.classList.add('et-html-cell');
            }
            if (typeof meta.renderer === 'function') {
                td.replaceChildren();
                meta.renderer.call(this, this, td, row, col, this._keyFor(col), value, meta);
            } else if (type === 'checkbox') {
                td.classList.add('et-checkbox-cell');
                var checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'et-checkbox';
                checkbox.tabIndex = -1;    //Keyboard input is handled by the grid.
                checkbox.checked = (value === true);
                checkbox.disabled = meta.readOnly;
                checkbox.setAttribute('aria-label', this._headerText(col) + ' (row ' + (row + 1) + ')');
                td.replaceChildren(checkbox);
            } else if (type === 'html') {
                EditableTable.setSafeHtml(td, value);
            } else {
                td.textContent = isEmptyValue(value) ? '' : String(value);
            }

            if (this._selected && this._selected.row === row && this._selected.col === col) {
                td.classList.add('et-selected');
            }
        }

        // ------------------------------------------------------------------
        //  Column and row helpers
        // ------------------------------------------------------------------

        _columnSetting(col, name) {
            var columns = this.settings.columns;
            if (Array.isArray(columns) && isIndex(col) && columns[col] &&
                    Object.prototype.hasOwnProperty.call(columns[col], name)) {
                return columns[col][name];
            }
            return undefined;
        }

        /**   The settings that apply to one cell: the column's settings, then
         * anything returned by the "cells" function. "type" is normalised to
         * 'checkbox', 'html' or 'text', and "readOnly" to true/false.
         *
         * @param {number} physicalRow -- The row's position in the loaded data.
         * @param {number} col
         */
        _cellMeta(physicalRow, col) {
            var columns = this.settings.columns;
            var meta = assignSafe({}, (Array.isArray(columns) && columns[col]) || {});
            if (typeof this.settings.cells === 'function') {
                assignSafe(meta, this.settings.cells.call(this, physicalRow, col, this._keyFor(col)));
            }
            meta.type = (meta.type === 'checkbox' || meta.type === 'html') ? meta.type : 'text';
            meta.readOnly = (meta.readOnly !== undefined) ? Boolean(meta.readOnly) : Boolean(this.settings.readOnly);
            return meta;
        }

        /** _cellMeta() for a visual row. */
        _metaAt(row, col) {
            var physicalRow = this._toPhysicalRow(row);
            return this._cellMeta(physicalRow === null ? row : physicalRow, col);
        }

        /**   The key a column reads from each row: its "data" property name for
         * object rows, otherwise the column index.
         */
        _keyFor(col) {
            var data = this._columnSetting(col, 'data');
            if ((typeof data === 'string' && data !== '') || typeof data === 'number') {
                return isSafeKey(data) ? data : null;    //null: the column stays empty.
            }
            if (this._sourceKeys && !Array.isArray(this.settings.columns) && col < this._sourceKeys.length) {
                return this._sourceKeys[col];
            }
            return col;
        }

        /**   Reads a cell. Only the row's own properties are read, never
         * inherited ones (such as "toString").
         */
        _valueAt(physicalRow, col) {
            var row = this._data[physicalRow];
            var key = this._keyFor(col);
            if (key === null || !Object.prototype.hasOwnProperty.call(row, key)) {
                return null;
            }
            var value = row[key];
            return (value === undefined) ? null : value;
        }

        _setValueAt(physicalRow, col, value) {
            var key = this._keyFor(col);
            if (key !== null) {
                this._data[physicalRow][key] = value;
            }
        }

        /**   One row's values as an array in column order. Array rows are
         * copied whole (padded with nulls to the column count).
         */
        _rowValues(physicalRow) {
            var row = this._data[physicalRow];
            if (Array.isArray(row)) {
                return this._normalizedRowCopy(row);
            }
            var values = [];
            for (var col = 0; col < this.countCols(); col++) {
                values.push(this._valueAt(physicalRow, col));
            }
            return values;
        }

        _headerText(col) {
            var title = this._columnSetting(col, 'title');
            if (!isEmptyValue(title)) {
                return String(title);
            }
            var headers = this.settings.colHeaders;
            if (Array.isArray(headers)) {
                return isEmptyValue(headers[col]) ? '' : String(headers[col]);
            }
            //colHeaders: true gives spreadsheet-style letters: A, B, ... Z, AA, ...
            var text = '';
            for (var n = col + 1; n > 0; n = Math.floor((n - 1) / 26)) {
                text = String.fromCharCode(65 + ((n - 1) % 26)) + text;
            }
            return text;
        }

        _hiddenColumnSet() {
            var hidden = this.settings.hiddenColumns;
            return new Set((hidden && Array.isArray(hidden.columns)) ? hidden.columns : []);
        }

        /** @returns {Array<number>} -- Indexes of the columns that are drawn. */
        _visibleColumns() {
            var hidden = this._hiddenColumnSet();
            var visible = [];
            for (var col = 0; col < this.countCols(); col++) {
                if (!hidden.has(col)) {
                    visible.push(col);
                }
            }
            return visible;
        }

        _toPhysicalRow(row) {
            if (!isIndex(row) || row >= this._order.length) {
                return null;
            }
            return this._order[row];
        }

        /** @returns {boolean} -- Whether "col" is a valid column index. */
        _isColumn(col) {
            return isIndex(col) && col < this.countCols();
        }

        /** Copies a row, padded with nulls to the full column count. */
        _normalizedRowCopy(row) {
            var copy = row.slice();
            for (var col = copy.length; col < this.countCols(); col++) {
                copy.push(null);
            }
            return copy.map(function (value) {
                return (value === undefined) ? null : value;
            });
        }

        _findCell(row, col) {
            //Both must be whole numbers, so nothing else can reach the selector.
            if (!this._table || !isIndex(row) || !isIndex(col)) {
                return null;
            }
            return this._table.querySelector('td[data-row="' + row + '"][data-col="' + col + '"]');
        }

        // ------------------------------------------------------------------
        //  Sorting
        // ------------------------------------------------------------------

        /**   Cycles a column's sort: unsorted -> ascending -> descending -> unsorted.
         * Empty cells always go last. Equal values keep their loaded order.
         */
        _cycleSort(col) {
            this._closeEditor(true, false);    //Keep any edit in progress first.

            var direction;
            if (!this._sort || this._sort.col !== col) {
                direction = 'asc';
            } else if (this._sort.direction === 'asc') {
                direction = 'desc';
            } else {
                direction = null;
            }

            var self = this;
            var order = this._data.map(function (row, index) {
                return index;
            });
            if (direction) {
                var sign = (direction === 'asc') ? 1 : -1;
                order.sort(function (a, b) {
                    var valueA = self._valueAt(a, col);
                    var valueB = self._valueAt(b, col);
                    var emptyA = isEmptyValue(valueA);
                    var emptyB = isEmptyValue(valueB);
                    if (emptyA || emptyB) {
                        return (emptyA === emptyB) ? a - b : (emptyA ? 1 : -1);
                    }
                    return (sign * compareValues(valueA, valueB)) || (a - b);
                });
            }

            this._order = order;
            this._sort = direction ? { col: col, direction: direction } : null;
            this._selected = null;
            this._reorderRows();
        }

        /**   Puts the existing rows into the current visual order and renumbers
         * them. Much faster than render() for big tables, because no cell has
         * to be rebuilt.
         */
        _reorderRows() {
            var table = this._table;
            if (!table || !table.tBodies[0] || !this._rowElements ||
                    this._rowElements.length !== this._order.length) {
                this.render();
                return;
            }
            var tbody = table.tBodies[0];
            var fragment = document.createDocumentFragment();
            for (var row = 0; row < this._order.length; row++) {
                var tr = this._rowElements[this._order[row]];
                var rowHeader = tr.querySelector('.et-row-header');
                if (rowHeader) {
                    rowHeader.textContent = String(row + 1);
                }
                for (var i = 0; i < tr.cells.length; i++) {
                    var cell = tr.cells[i];
                    if (cell.tagName === 'TD') {
                        cell.dataset.row = row;
                        var checkbox = cell.querySelector('.et-checkbox');
                        if (checkbox) {
                            checkbox.setAttribute('aria-label',
                                this._headerText(Number(cell.dataset.col)) + ' (row ' + (row + 1) + ')');
                        }
                    }
                }
                fragment.appendChild(tr);
            }
            tbody.appendChild(fragment);
            if (this.settings.colHeaders && table.tHead) {
                table.replaceChild(this._buildHead(), table.tHead);
            }
            this._showSelection();
        }

        // ------------------------------------------------------------------
        //  Selection
        // ------------------------------------------------------------------

        _select(row, col, scrollIntoView) {
            this._selected = { row: row, col: col };
            this._showSelection();
            var td = this._findCell(row, col);
            if (td && scrollIntoView) {
                td.scrollIntoView({ block: 'nearest', inline: 'nearest' });
            }
        }

        _deselect() {
            this._selected = null;
            this._showSelection();
        }

        /** Highlights the selected cell, plus its row and column headers. */
        _showSelection() {
            if (!this._table) {
                return;
            }
            this._table.querySelectorAll('.et-selected, .et-header-active').forEach(function (element) {
                element.classList.remove('et-selected', 'et-header-active');
            });
            if (!this._selected) {
                return;
            }
            var td = this._findCell(this._selected.row, this._selected.col);
            if (!td) {
                this._selected = null;
                return;
            }
            td.classList.add('et-selected');
            var rowHeader = td.parentElement.querySelector('.et-row-header');
            if (rowHeader) {
                rowHeader.classList.add('et-header-active');
            }
            var colHeader = this._table.querySelector('th.et-col-header[data-col="' + this._selected.col + '"]');
            if (colHeader) {
                colHeader.classList.add('et-header-active');
            }
        }

        /**   Works out where the selection goes after a navigation key.
         *
         * @param {number} rowStep -- -1, 0 or 1.
         * @param {number} colStep -- -1, 0 or 1 (in visible columns).
         * @param {boolean} wrap -- Whether to wrap onto the next/previous row
         *      or column at the edges.
         * @returns {{row:number, col:number}|null} -- null when there is
         *      nowhere to go.
         */
        _neighbour(rowStep, colStep, wrap) {
            var columns = this._visibleColumns();
            var rowCount = this._order.length;
            var row = this._selected.row;
            var colPosition = columns.indexOf(this._selected.col);

            if (colStep !== 0) {
                colPosition += colStep;
                if (colPosition >= columns.length) {
                    if (!wrap || row + 1 >= rowCount) {
                        return null;
                    }
                    colPosition = 0;
                    row++;
                } else if (colPosition < 0) {
                    if (!wrap || row - 1 < 0) {
                        return null;
                    }
                    colPosition = columns.length - 1;
                    row--;
                }
            }
            if (rowStep !== 0) {
                row += rowStep;
                if (row >= rowCount) {
                    if (!wrap || colPosition + 1 >= columns.length) {
                        return null;
                    }
                    row = 0;
                    colPosition++;
                } else if (row < 0) {
                    if (!wrap || colPosition - 1 < 0) {
                        return null;
                    }
                    row = rowCount - 1;
                    colPosition--;
                }
            }
            return { row: row, col: columns[colPosition] };
        }

        _move(rowStep, colStep, wrap) {
            var target = this._neighbour(rowStep, colStep, wrap);
            if (target) {
                this._select(target.row, target.col, true);
            }
            return target !== null;
        }

        // ------------------------------------------------------------------
        //  Editing
        // ------------------------------------------------------------------

        _toggleCheckbox(row, col) {
            if (this._metaAt(row, col).readOnly) {
                return;
            }
            this.setDataAtCell(row, col, this.getDataAtCell(row, col) !== true);
        }

        /**   Opens the in-cell text editor.
         *
         * @param {number} row -- Visual row.
         * @param {number} col
         * @param {string|null} initialText -- Replaces the value when given
         *      (the user started typing); otherwise the current value is edited.
         */
        _openEditor(row, col, initialText) {
            var meta = this._metaAt(row, col);
            if (meta.readOnly || meta.type === 'checkbox') {
                return;
            }
            var td = this._findCell(row, col);
            if (!td) {
                return;
            }
            this._closeEditor(true, false);
            this._select(row, col, true);

            var value = this.getDataAtCell(row, col);
            var textarea = document.createElement('textarea');
            textarea.className = 'et-editor';
            textarea.rows = 1;
            textarea.value = (initialText !== null) ? initialText : (isEmptyValue(value) ? '' : String(value));
            textarea.setAttribute('aria-label', this._headerText(col) + ' (row ' + (row + 1) + ')');

            td.classList.add('et-editing');
            td.replaceChildren(textarea);
            this._editor = { row: row, col: col, td: td, textarea: textarea, originalValue: value };

            var self = this;
            var fitHeight = function () {
                textarea.style.height = 'auto';
                textarea.style.height = textarea.scrollHeight + 'px';
            };
            textarea.addEventListener('input', fitHeight);
            textarea.addEventListener('keydown', this._onEditorKeyDown.bind(this));
            textarea.addEventListener('blur', function () {
                self._closeEditor(true, false);    //Clicking elsewhere saves.
            });

            fitHeight();
            textarea.focus({ preventScroll: true });
            textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        }

        /**   Closes the text editor, if open.
         *
         * @param {boolean} save -- Whether to keep what was typed.
         * @param {boolean} refocusGrid -- Whether to return keyboard focus to the grid.
         */
        _closeEditor(save, refocusGrid) {
            var editor = this._editor;
            if (!editor) {
                return;
            }
            this._editor = null;    //Set first: focus changes below fire "blur" again.

            var newText = cleanUserText(editor.textarea.value);
            if (refocusGrid && this._wrapper) {
                this._wrapper.focus({ preventScroll: true });
            }
            var oldText = isEmptyValue(editor.originalValue) ? '' : String(editor.originalValue);
            if (save && newText !== oldText) {
                this._setValueAt(this._order[editor.row], editor.col, newText);
            }
            if (editor.td.isConnected) {
                this._fillCell(editor.td, editor.row, editor.col);
            }
        }

        _onEditorKeyDown(event) {
            event.stopPropagation();    //The grid's own key handling must not run.
            switch (event.key) {
                case 'Enter':
                    event.preventDefault();
                    this._closeEditor(true, true);
                    this._move(event.shiftKey ? -1 : 1, 0, false);
                    break;
                case 'Tab':
                    event.preventDefault();
                    this._closeEditor(true, true);
                    this._move(0, event.shiftKey ? -1 : 1, true);
                    break;
                case 'Escape':
                    event.preventDefault();
                    this._closeEditor(false, true);
                    break;
                default:
                    break;    //Normal typing, and arrow keys move the caret.
            }
        }

        // ------------------------------------------------------------------
        //  Event handlers
        // ------------------------------------------------------------------

        _cellFromEvent(event) {
            var td = event.target.closest('td.et-cell');
            if (!td || !this._wrapper.contains(td)) {
                return null;
            }
            return { td: td, row: Number(td.dataset.row), col: Number(td.dataset.col) };
        }

        _onMouseDown(event) {
            if (event.button !== 0) {
                return;
            }
            if (this._editor && event.target === this._editor.textarea) {
                return;    //Let the user place the caret inside the editor.
            }
            var cell = this._cellFromEvent(event);
            var onHeader = event.target.closest('th');
            if (!cell && !onHeader) {
                return;    //E.g. the scrollbar: leave the default behaviour alone.
            }
            //Stop text selection and stop the checkbox taking focus; the grid
            //keeps focus so that the keyboard keeps working. (The checkbox's
            //"click" still happens, and still toggles it.) Clicks on a
            //scrollbar inside a cell are left alone, so it can be dragged.
            var target = event.target;
            var onInnerScrollbar = (target !== this._wrapper && target.clientWidth > 0 &&
                (event.offsetX > target.clientWidth || event.offsetY > target.clientHeight));
            if (!onInnerScrollbar) {
                event.preventDefault();
            }
            this._closeEditor(true, false);
            this._wrapper.focus({ preventScroll: true });
            if (cell) {
                this._select(cell.row, cell.col, false);
                this._runHook('afterOnCellMouseDown', event, cell);
            }
        }

        _onMouseUp(event) {
            if (event.button !== 0) {
                return;
            }
            var cell = this._cellFromEvent(event);
            if (cell) {
                this._runHook('afterOnCellMouseUp', event, cell);
            }
        }

        /**   Calls the functions for a mouse hook: first the one in the
         * settings, then any added with addHook().
         */
        _runHook(name, event, cell) {
            var coords = { row: cell.row, col: cell.col };
            var fns = [this.settings[name]].concat(this._hooks.get(name) || []);
            for (var i = 0; i < fns.length; i++) {
                if (typeof fns[i] === 'function') {
                    fns[i].call(this, event, coords, cell.td);
                }
            }
        }

        _onClick(event) {
            var th = event.target.closest('th.et-sortable');
            if (th && this._wrapper.contains(th)) {
                this._cycleSort(Number(th.dataset.col));
            }
        }

        _onDoubleClick(event) {
            var cell = this._cellFromEvent(event);
            if (cell && this._metaAt(cell.row, cell.col).type !== 'checkbox') {
                this._openEditor(cell.row, cell.col, null);
            }
        }

        _onChange(event) {
            if (!event.target.classList.contains('et-checkbox')) {
                return;
            }
            var cell = this._cellFromEvent(event);
            if (!cell) {
                return;
            }
            var meta = this._metaAt(cell.row, cell.col);
            if (meta.readOnly || meta.type !== 'checkbox') {
                //E.g. a disabled checkbox was re-enabled with developer tools:
                //put the stored value back.
                this._fillCell(cell.td, cell.row, cell.col);
                return;
            }
            this.setDataAtCell(cell.row, cell.col, event.target.checked);
        }

        _onCopy(event) {
            if (event.target !== this._wrapper || !this._selected || !event.clipboardData) {
                return;
            }
            var value = this.getDataAtCell(this._selected.row, this._selected.col);
            event.clipboardData.setData('text/plain', isEmptyValue(value) ? '' : String(value));
            event.preventDefault();
        }

        _onPaste(event) {
            if (event.target !== this._wrapper || !this._selected || !event.clipboardData) {
                return;
            }
            event.preventDefault();
            var row = this._selected.row;
            var col = this._selected.col;
            var meta = this._metaAt(row, col);
            if (meta.readOnly) {
                return;
            }
            var text = cleanUserText(event.clipboardData.getData('text/plain').replace(/\r?\n$/, ''));
            if (meta.type === 'checkbox') {
                var lowered = text.trim().toLowerCase();
                if (lowered === 'true' || lowered === 'false') {
                    this.setDataAtCell(row, col, lowered === 'true');
                }
            } else {
                this.setDataAtCell(row, col, text);
            }
        }

        _onFocusOut(event) {
            //Focus has left the table altogether: drop the selection.
            if (!this.container.contains(event.relatedTarget)) {
                this._deselect();
            }
        }

        _onKeyDown(event) {
            if (event.target !== this._wrapper || this._order.length === 0) {
                return;
            }
            if (event.ctrlKey || event.metaKey || event.altKey) {
                return;    //Leave browser shortcuts (copy, reload, ...) alone.
            }
            var wrapRows = Boolean(this.settings.autoWrapRow);
            var wrapCols = Boolean(this.settings.autoWrapCol);

            if (!this._selected) {
                //Arrow keys or Enter start at the first cell; Tab passes through.
                if (['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Enter'].indexOf(event.key) !== -1) {
                    event.preventDefault();
                    var firstColumn = this._visibleColumns()[0];
                    if (firstColumn !== undefined) {
                        this._select(0, firstColumn, true);
                    }
                }
                return;
            }

            var row = this._selected.row;
            var col = this._selected.col;
            var meta = this._metaAt(row, col);
            var isCheckbox = (meta.type === 'checkbox');

            switch (event.key) {
                case 'ArrowUp':
                    event.preventDefault();
                    this._move(-1, 0, wrapCols);
                    break;
                case 'ArrowDown':
                    event.preventDefault();
                    this._move(1, 0, wrapCols);
                    break;
                case 'ArrowLeft':
                    event.preventDefault();
                    this._move(0, -1, wrapRows);
                    break;
                case 'ArrowRight':
                    event.preventDefault();
                    this._move(0, 1, wrapRows);
                    break;
                case 'Tab':
                    //Move between cells; at either end of the table, let Tab
                    //leave the grid so keyboard users are never trapped.
                    if (this._move(0, event.shiftKey ? -1 : 1, true)) {
                        event.preventDefault();
                    }
                    break;
                case 'Enter':
                case 'F2':
                    event.preventDefault();
                    if (isCheckbox) {
                        if (event.key === 'Enter') {
                            this._toggleCheckbox(row, col);
                        }
                    } else {
                        this._openEditor(row, col, null);
                    }
                    break;
                case ' ':
                    if (isCheckbox) {
                        event.preventDefault();    //Also stops the page scrolling.
                        this._toggleCheckbox(row, col);
                    } else {
                        event.preventDefault();
                        this._openEditor(row, col, ' ');
                    }
                    break;
                case 'Delete':
                case 'Backspace':
                    event.preventDefault();
                    if (!meta.readOnly) {
                        this.setDataAtCell(row, col, isCheckbox ? false : '');
                    }
                    break;
                default:
                    //A printable character starts a new value in a text cell.
                    if (event.key.length === 1 && !isCheckbox) {
                        event.preventDefault();
                        this._openEditor(row, col, event.key);
                    }
                    break;
            }
        }
    }

    // ----------------------------------------------------------------------
    //  Static helpers for HTML content
    // ----------------------------------------------------------------------

    //Formatting tags that are kept (without any attributes) by setSafeHtml():
    var SAFE_TAGS = new Set(['B', 'STRONG', 'I', 'EM', 'U', 'S', 'SMALL', 'MARK', 'SUB', 'SUP',
        'BR', 'HR', 'P', 'DIV', 'SPAN', 'UL', 'OL', 'LI', 'BLOCKQUOTE', 'PRE', 'CODE',
        'H1', 'H2', 'H3', 'H4', 'H5', 'H6']);
    //Tags that are removed together with everything inside them:
    var DROPPED_TAGS = new Set(['SCRIPT', 'STYLE', 'TEMPLATE', 'IFRAME', 'OBJECT', 'EMBED',
        'NOSCRIPT', 'SVG', 'MATH', 'TEXTAREA', 'SELECT', 'BUTTON', 'HEAD', 'TITLE']);

    //Formatting tags nested deeper than this are flattened to plain text, so
    //that hostile, very deeply nested HTML cannot exhaust the call stack.
    var MAX_HTML_DEPTH = 64;

    /**   Appends the text inside "sourceParent" to "targetParent" as a single
     * text node, skipping the contents of DROPPED_TAGS. Not recursive.
     */
    function copyTextOnly(sourceParent, targetParent) {
        var walker = sourceParent.ownerDocument.createTreeWalker(sourceParent,
            NodeFilter.SHOW_ELEMENT | NodeFilter.SHOW_TEXT, {
                acceptNode: function (node) {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        return DROPPED_TAGS.has(node.tagName.toUpperCase()) ?
                            NodeFilter.FILTER_REJECT : NodeFilter.FILTER_SKIP;
                    }
                    return NodeFilter.FILTER_ACCEPT;
                }
            });
        var text = '';
        while (walker.nextNode()) {
            text += walker.currentNode.nodeValue;
        }
        if (text !== '') {
            targetParent.appendChild(document.createTextNode(text));
        }
    }

    /**   Copies the safe parts of a parsed node tree into a new fragment. Other
     * tags are "unwrapped": their text is kept, but the tag itself is not.
     * Only new, attribute-free elements and text nodes are created; nothing
     * from the parsed document is moved, and nothing is re-parsed as HTML.
     *
     * @param {number} depth -- How many tags deep "targetParent" is.
     */
    function copySafeNodes(sourceParent, targetParent, depth) {
        if (depth >= MAX_HTML_DEPTH) {
            copyTextOnly(sourceParent, targetParent);
            return;
        }
        sourceParent.childNodes.forEach(function (node) {
            if (node.nodeType === Node.TEXT_NODE) {
                targetParent.appendChild(document.createTextNode(node.nodeValue));
            } else if (node.nodeType === Node.ELEMENT_NODE) {
                var tag = node.tagName.toUpperCase();
                if (DROPPED_TAGS.has(tag)) {
                    return;
                }
                if (SAFE_TAGS.has(tag)) {
                    var copy = document.createElement(tag.toLowerCase());    //No attributes.
                    copySafeNodes(node, copy, depth + 1);
                    targetParent.appendChild(copy);
                } else {
                    copySafeNodes(node, targetParent, depth + 1);
                }
            }
            //Comments and other node types are dropped.
        });
    }

    /**   Shows an HTML string inside an element, keeping only basic formatting
     * tags (no attributes, scripts, images, links, styles or event handlers).
     * The string is parsed in a separate, inert document, so nothing in it
     * runs or loads while it is being cleaned.
     *
     * @param {HTMLElement} element -- Its current contents are replaced.
     * @param {*} html -- The HTML string (null/undefined show as empty).
     */
    EditableTable.setSafeHtml = function (element, html) {
        if (!(element instanceof HTMLElement)) {
            throw new TypeError('EditableTable.setSafeHtml: "element" must be a DOM element.');
        }
        var fragment = document.createDocumentFragment();
        if (!isEmptyValue(html)) {
            var parsed = new DOMParser().parseFromString(String(html), 'text/html');
            copySafeNodes(parsed.body, fragment, 0);
        }
        element.replaceChildren(fragment);
    };

    /**   Wraps every case-insensitive match of "searchText" in the element's
     * visible text with <span class="className">. Tags and attributes are
     * never searched, so the HTML structure is left intact. The search text
     * is matched literally (characters such as "." or "(" have no special
     * meaning).
     *
     * @param {HTMLElement} element
     * @param {string} searchText
     * @param {string} className -- E.g. 'yellowit'.
     * @returns {number} -- How many matches were highlighted.
     */
    EditableTable.highlightText = function (element, searchText, className) {
        if (isEmptyValue(searchText)) {
            return 0;
        }
        var escaped = String(searchText).replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        var pattern = new RegExp(escaped, 'gi');
        var walker = document.createTreeWalker(element, NodeFilter.SHOW_TEXT);
        var textNodes = [];
        while (walker.nextNode()) {
            textNodes.push(walker.currentNode);
        }

        var count = 0;
        textNodes.forEach(function (textNode) {
            var text = textNode.nodeValue;
            var matches = Array.from(text.matchAll(pattern));
            if (matches.length === 0) {
                return;
            }
            var fragment = document.createDocumentFragment();
            var position = 0;
            matches.forEach(function (match) {
                if (match.index > position) {
                    fragment.appendChild(document.createTextNode(text.slice(position, match.index)));
                }
                var span = document.createElement('span');
                span.className = className;
                span.textContent = match[0];
                fragment.appendChild(span);
                position = match.index + match[0].length;
                count++;
            });
            if (position < text.length) {
                fragment.appendChild(document.createTextNode(text.slice(position)));
            }
            textNode.parentNode.replaceChild(fragment, textNode);
        });
        return count;
    };

    global.EditableTable = EditableTable;

})(window);
