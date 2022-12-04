<template>
  <div class="wswg-editor shadow">

      <div class="menubar">
        <div class="overflow-hidden">

          <div class="_group">

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('paragraph') || editor.isActive('bulletList') || editor.isActive('orderedList')}"
                @click="editor.chain().focus().setParagraph().run()"
            >
              <i class="bi bi-paragraph"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('heading', {level: 2})}"
                @click="editor.chain().focus().toggleHeading({level: 2}).run()"
            >
              <span>H2</span>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('heading', {level: 3})}"
                @click="editor.chain().focus().toggleHeading({level: 3}).run()"
            >
              <span>H3</span>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('heading', {level: 4})}"
                @click="editor.chain().focus().toggleHeading({level: 4}).run()"
            >
              <span>H4</span>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('heading', {level: 5})}"
                @click="editor.chain().focus().toggleHeading({level: 5}).run()"
            >
              <span>H5</span>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('heading', {level: 6})}"
                @click="editor.chain().focus().toggleHeading({level: 6}).run()"
            >
              <span>H6</span>
            </button>

          </div>
          <div class="_group">

            <button
                type="button"
                class="_button"
                :class="{'is-active': colorPanel}"
                @click="colorPanel = true"
            >
              <i class="bi bi-palette-fill"></i>
            </button>
          </div>

          <div class="_group">

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('bold')}"
                @click="editor.chain().focus().toggleBold().run()"
            >
              <i class="bi bi-type-bold"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('italic')}"
                @click="editor.chain().focus().toggleItalic().run()"
            >
              <i class="bi bi-type-italic"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('strike')}"
                @click="editor.chain().focus().toggleStrike().run()"
            >
              <i class="bi bi-type-strikethrough"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('underline')}"
                @click="editor.chain().focus().toggleUnderline().run()"
            >
              <i class="bi bi-type-underline"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('blockquote')}"
                @click="editor.chain().focus().toggleBlockquote().run()"
            >
              <i class="bi bi-quote"></i>
            </button>

          </div>

          <div class="_group">

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('bulletList')}"
                @click="editor.chain().focus().unsetBlockquote().toggleBulletList().run()"
            >
              <i class="bi bi-list-ul"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('orderedList')}"
                @click="editor.chain().focus().unsetBlockquote().toggleOrderedList().run()"
            >
              <i class="bi bi-list-ol"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'pe-disabled o-20': !editor.can().liftListItem('listItem')}"
                @click="editor.chain().focus().liftListItem('listItem').run()"
            >
              <i class="bi bi-caret-left-fill"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'pe-disabled o-20': !editor.can().sinkListItem('listItem')}"
                @click="editor.chain().focus().sinkListItem('listItem').run()"
            >
              <i class="bi bi-caret-right-fill"></i>
            </button>

          </div>

          <div class="_group">

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('link')}"
                @click="setLink"
            >
              <i class="bi bi-link"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('image')}"
                @click="addImage"
            >
              <i class="bi bi-image-fill"></i>
            </button>

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('horizontalRule')}"
                @click="editor.chain().focus().setHorizontalRule().run()"
            >
              <i class="bi bi-hr"></i>
            </button>

          </div>

          <div class="_group">

            <button
                type="button"
                class="_button"
                :class="{'is-active': editor.isActive('table')}"
                @click="editor.chain().focus().insertTable({ rows: 1, cols: 3, withHeaderRow: false }).run()"
            >
              <i class="bi bi-table"></i>
            </button>

          </div>

          <div class="_group">

            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().undo().run()"
            >
              <i class="bi bi-arrow-90deg-left"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().redo().run()"
            >
              <i class="bi bi-arrow-90deg-right"></i>
            </button>

          </div>

        </div>

        <div class="overflow-hidden" v-if="editor.isActive('table')">

          <div class="_group">

            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().deleteTable().run()"
            >
              <i class="bi bi-trash3-fill"></i>
            </button>
            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().addColumnBefore().run()"
            >
              +<i class="bi bi-border-middle"></i>
            </button>
            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().addColumnAfter().run()"
            >
              <i class="bi bi-border-middle"></i>+
            </button>
            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().deleteColumn().run()"
            >
              <i class="bi bi-border-middle"></i>-
            </button>
            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().addRowBefore().run()"
            >
              +<i class="bi bi-border-center"></i>
            </button>
            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().addRowAfter().run()"
            >
              <i class="bi bi-border-center"></i>+
            </button>
            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().deleteRow().run()"
            >
              <i class="bi bi-border-center"></i>-
            </button>
            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().mergeCells().run()"
            >
              <i class="bi bi-view-stacked"></i>+<i class="bi bi-view-stacked"></i>
            </button>
            <button
                type="button"
                class="_button"
                @click="editor.chain().focus().toggleHeaderCell().run()"
            >
              <i class="bi bi-card-heading"></i>
            </button>
          </div>
        </div>



        <div class="overflow-hidden" v-if="colorPanel">

          <div class="_group">

            <button
                type="button"
                class="_button"
                @click="colorPanel = false"
            >
              <i class="bi bi-x-lg"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#FF0000', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-1-square-fill"></i>
            </button>
            <button
                type="button"
                class="_button"
                @click="selectedColor = '#FF0000', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-2-square-fill"></i>
            </button>
          </div>
            <div class="_group">

            <input
                type="color"
                class="color-picker"
                @click="colorPanel = true"
                @input="editor.chain().focus().setColor($event.target.value).run()"
                :value="editor.getAttributes('textStyle').color"
            >
            <button
                type="button"
                class="_button"
                @click="selectedColor = '#FF0000', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#FF0000;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#00FFFF', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#00FFFF;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#0000FF', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#0000FF;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#00008B', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#00008B;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#ADD8E6', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#ADD8E6;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#800080', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#800080;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#FFFF00', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#FFFF00;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#00FF00', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#00FF00;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#FF00FF', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#FF00FF;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#FFC0CB', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#FFC0CB;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#FFFFFF', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#FFFFFF;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#808080', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#808080;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#000000', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#000000;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#FFA500', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#FFA500;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#800000', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#800000;"></i>
            </button>

            <button
                type="button"
                class="_button"
                @click="selectedColor = '#008000', editor.chain().focus().setColor(selectedColor).run()"
            >
              <i class="bi bi-square-fill" style="color:#008000;"></i>
            </button>
          </div>
        </div>
      </div>
    <editor-content :editor="editor" />
  </div>
</template>

<script>
import {Editor, EditorContent} from '@tiptap/vue-2';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Table from '@tiptap/extension-table';
import TableRow from '@tiptap/extension-table-row';
import TableCell from '@tiptap/extension-table-cell';
import TableHeader from '@tiptap/extension-table-header';
import TextStyle from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';
import Highlight from '@tiptap/extension-highlight'
import {i18n} from "../app";

export default {
  //components: {EditorMenuBar, EditorContent},
  components: {EditorContent},
  props: ['value'],
  data() {
    return {
      colorPanel:false,
      color1:'',
      color2:'',

      editor: new Editor({
        content: this.value,
        extensions: [
          StarterKit.configure({
            heading: {
              levels: [2, 3, 4, 5, 6],
            },
          }),
          Underline,
          Link.configure({
            openOnClick: false,
            HTMLAttributes: {
              target: '_self',
              rel: ''
            },
          }),
          Image,
          Table,
          TableRow,
          TableHeader,
          TableCell,
          TextStyle,
          Color,
          Highlight.configure({ multicolor: true }),
        ],
        onCreate: () => {
          this.update();
        },
        onUpdate: () => {
          this.update();
        },
      }),
    };
  },
  watch: {
    value(current) {
      console.log(current);
      if (current !== this.editor.getHTML()) {
        if (current === ''){
          this.editor.commands.clearContent(true);
        }
        else {
          this.editor.setContent(current);
        }

      }
    },
  },
  methods: {
    getHTML() {
      const html = this.editor.getHTML();
      return html === '<p></p>' ? '' : html;
    },
    update() {
      let html = this.getHTML();
      html = _.replace(html, new RegExp(this.Laravel('appUrl'), 'g'), '');
      $(".ProseMirror video").prop('muted', true);
      this.$emit('input', html);
    },
    setLink() {
      const current = this.editor.getAttributes('link').href;
      const href = prompt('Zadajte URL na prelink:', current || '');
      if (href !== null) {
        this.editor.chain().focus().extendMarkRange('link').setLink({href}).run();
      }
    },
    addImage() {
      const current = this.editor.getAttributes('image').src;
      const src = prompt('Zadajte URL obrázku:', current || '');
      if (src) {
        this.editor.chain().focus().setImage({src}).run();
      }
    },

  },
  beforeDestroy() {
    this.editor.destroy();
  },
};
</script>

<style lang="scss" scoped>
@import 'resources/sass/variables';

</style>
