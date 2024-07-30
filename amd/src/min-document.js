// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Defines document script.
 *
 * @since      Moodle 2.0
 * @copyright  2023 Moodle India
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(function (require, exports, module) {
var domWalk = require("./dom-walk");
var Comment = require("./dom-comment.js");
var DOMText = require("./dom-text.js");
var DOMElement = require("./dom-element.js");
var DocumentFragment = require("./dom-fragment.js");
var Event = require("./event.js");
var dispatchEvent = require("./event/dispatch-event.js");
var addEventListener = require("./event/add-event-listener.js");
var removeEventListener = require("./event/remove-event-listener.js");
module.exports = Document;
/**
 * function Document
 */
function Document() {
    if (!(this instanceof Document)) {
        return new Document();
    }
    this.head = this.createElement("head");
    this.body = this.createElement("body");
    this.documentElement = this.createElement("html");
    this.documentElement.appendChild(this.head);
    this.documentElement.appendChild(this.body);
    this.childNodes = [this.documentElement];
    this.nodeType = 9;
}
var proto = Document.prototype;
proto.createTextNode = function createTextNode(value) {
    return new DOMText(value, this);
};
proto.createElementNS = function createElementNS(namespace, tagName) {
    var ns = namespace === null ? null : String(namespace);
    return new DOMElement(tagName, this, ns);
};
proto.createElement = function createElement(tagName) {
    return new DOMElement(tagName, this);
};
proto.createDocumentFragment = function createDocumentFragment() {
    return new DocumentFragment(this);
};
proto.createEvent = function createEvent(family) {
    return new Event(family);
};
proto.createComment = function createComment(data) {
    return new Comment(data, this);
};
proto.getElementById = function getElementById(id) {
    id = String(id);
    var result = domWalk(this.childNodes, function (node) {
        if (String(node.id) === id) {
            return node;
        }
    });
    return result || null;
};
proto.getElementsByClassName = DOMElement.prototype.getElementsByClassName;
proto.getElementsByTagName = DOMElement.prototype.getElementsByTagName;
proto.contains = DOMElement.prototype.contains;
proto.removeEventListener = removeEventListener;
proto.addEventListener = addEventListener;
proto.dispatchEvent = dispatchEvent;
});