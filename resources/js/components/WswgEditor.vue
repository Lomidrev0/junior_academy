<template>
  <div class="wswg-editor shadow">
    <editor-menu-bar :editor="editor" v-slot="{ commands, getMarkAttrs, isActive }">
      <div class="menubar">
        <div class="overflow-hidden">

          <div class="menubar__group">

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.paragraph() }"
                @click="commands.paragraph"
            >
              <i class="bi bi-paragraph"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.heading({ level: 2 }) }"
                @click="commands.heading({ level: 2 })"
            >
              <span><b>H2</b></span>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.heading({ level: 3 }) }"
                @click="commands.heading({ level: 3 })"
            >
              <span><b>H3</b></span>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.heading({ level: 4 }) }"
                @click="commands.heading({ level: 4 })"
            >
              <span><b>H4</b></span>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.heading({ level: 5 }) }"
                @click="commands.heading({ level: 5 })"
            >
              <span><b>H5</b></span>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.heading({ level: 6 }) }"
                @click="commands.heading({ level: 6 })"
            >
              <span><b>H6</b></span>
            </button>

          </div>

          <div class="menubar__group">

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.bold() }"
                @click="commands.bold"
            >
              <i class="bi bi-type-bold"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.italic() }"
                @click="commands.italic"
            >
              <i class="bi bi-type-italic"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.strike() }"
                @click="commands.strike"
            >
              <i class="bi bi-type-strikethrough"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.underline() }"
                @click="commands.underline"
            >
              <i class="bi bi-type-underline"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.blockquote() }"
                @click="commands.blockquote"
            >
              <i class="bi bi-quote"></i>
            </button>

          </div>

          <div class="menubar__group">

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.bullet_list() }"
                @click="commands.bullet_list"
            >
              <i class="bi bi-list-ul"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.ordered_list() }"
                @click="commands.ordered_list"
            >
              <i class="bi bi-list-ol"></i>
            </button>

          </div>

          <div class="menubar__group">

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.link() }"
                @click="showLinkPrompt(getMarkAttrs('link'), commands.link)"
            >
              <i class="bi bi-link"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                :class="{ 'is-active': isActive.image() }"
                @click="showImagePrompt(commands.image)"
            >
              <i class="bi bi-card-image"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                @click="commands.createTable({rowsCount: 1, colsCount: 3, withHeaderRow: false})"
            >
              <i class="bi bi-table"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                @click="commands.horizontal_rule"
            >
              <i class="bi bi-hr"></i>
            </button>

          </div>

          <div class="menubar__group">

            <button
                type="button"
                class="menubar__button"
                @click="commands.undo"
            >
              <i class="bi bi-arrow-90deg-left"></i>
            </button>

            <button
                type="button"
                class="menubar__button"
                @click="commands.redo"
            >
              <i class="bi bi-arrow-90deg-right"></i>
            </button>

          </div>

        </div>

        <div class="overflow-hidden" v-if="isActive.table()">

          <div class="menubar__group xdddddd">

            <button
                type="button"
                class="menubar__button"
                @click="commands.deleteTable"
            >
              <i class="bi bi-trash3-fill"></i>
            </button>
            <button
                type="button"
                class="menubar__button"
                @click="commands.addColumnBefore"
            >
              <b>+</b><i class="bi bi-border-middle"></i>
            </button>
            <button
                type="button"
                class="menubar__button"
                @click="commands.addColumnAfter"
            >
              <i class="bi bi-border-middle"></i><b>+</b>
            </button>
            <button
                type="button"
                class="menubar__button"
                @click="commands.deleteColumn"
            >
              <i class="bi bi-border-middle"></i><b>-</b>
            </button>
            <button
                type="button"
                class="menubar__button"
                @click="commands.addRowBefore"
            >
              <b>	˄</b><i class="bi bi-border-center"></i>
            </button>
            <button
                type="button"
                class="menubar__button"
                @click="commands.addRowAfter"
            >
              <i class="bi bi-border-center"></i><b>˅</b>
            </button>
            <button
                type="button"
                class="menubar__button"
                @click="commands.deleteRow"
            >
              <i class="bi bi-border-center"></i><b>-</b>
            </button>
            <button
                type="button"
                class="menubar__button"
                @click="commands.toggleCellMerge"
            >
              <i class="bi bi-view-stacked"></i>+<i class="bi bi-view-stacked"></i>
            </button>

          </div>

        </div>

      </div>
    </editor-menu-bar>
    <editor-content :editor="editor" />
  </div>
</template>

<script>
import {Editor, EditorContent, EditorMenuBar} from 'tiptap';
import {
  Blockquote,
  HardBreak,
  Heading,
  HorizontalRule,
  OrderedList,
  BulletList,
  ListItem,
  TodoItem,
  TodoList,
  Bold,
  Italic,
  Link,
  Strike,
  Underline,
  History,
  Image,
  Table,
  TableHeader,
  TableCell,
  TableRow,
} from 'tiptap-extensions';
import {i18n} from "../app";

export default {
  components: {EditorMenuBar, EditorContent},
  props: ['value'],
  data() {
    return {
      editor: new Editor({
        content: this.value,
        extensions: [
          new Blockquote(),
          new BulletList(),
          new HardBreak(),
          new Heading({levels: [2, 3, 4, 5, 6]}),
          new HorizontalRule(),
          new ListItem(),
          new OrderedList(),
          new TodoItem(),
          new TodoList(),
          new Link({
            openOnClick: false,
          }),
          new Bold(),
          new Italic(),
          new Strike(),
          new Underline(),
          new History(),
          new Image(),
          new Table({
            resizable: true,
          }),
          new TableHeader(),
          new TableCell(),
          new TableRow(),
        ],
        onUpdate: ({getHTML, getJSON}) => {
          this.update(getHTML(), getJSON());
        },
      }),
    };
  },
  watch: {
    value(current) {
      if (current !== this.editor.getHTML()) {
        this.editor.setContent(current);
      }
    },
  },
  methods: {
    update(html, json) {
      json = json.content;
      if (Array.isArray(json) && json.length === 1 && !json[0].hasOwnProperty('content')) {
        html = '';
      }
      this.$emit('input', html);
    },
    showLinkPrompt({href}, command) {
      const linkUrl = prompt(i18n("Enter link URL:"), href);
      command({href: linkUrl});
    },
    showImagePrompt(command) {
      const src = prompt(i18n("Enter image URL:"));
      if (src !== null) {
        command({ src });
      }
    },
  },
  mounted() {
    this.update(this.editor.getHTML(), this.editor.getJSON());
  },
  beforeDestroy() {
    this.editor.destroy();
  },
};
</script>

<style lang="scss" scoped>
@import 'resources/sass/variables';
::v-deep .ProseMirror {
  min-height: 50vh;
  max-width: 160vh;
  outline: none;
  padding: 12px 15px;

  ::selection {
    background-color: $blue;
    color: $white;
    opacity: 0 !important;
  }

  table {
    width: 100%;
    table-layout: fixed;

    td {
      min-width: 1em;
      padding: 6px;
      border: 1px solid black;
      vertical-align: top;
      position: relative;
      > * {
        margin-bottom: 0;
      }
    }

    .selectedCell {
      background-color: $blue;
      border: 0.5px solid $white;
      color: $white;
    }

    .column-resize-handle {
      position: absolute;
      right: -2px; top: 0; bottom: 0;
      width: 4px;
      z-index: 20;
      background-color: var(--main);
      pointer-events: none;
    }
  }

  blockquote {
    border-left: 3px solid var(--background-3);
    color: var(--text-2);
    padding-left: 0.8rem;
    font-style: italic;

    p {
      margin: 0;
    }
  }

  .tableWrapper {
    margin: 1em 0;
    overflow-x: auto;
  }

  p, h2, h3, h4, h5, h6, ul, ol {
    margin-top: 0;
    margin-bottom: 1rem;

    &:last-child {
      margin-bottom: 0;
    }
  }

  &.resize-cursor {
    //cursor: ew-resize;
    cursor: col-resize;
  }

  img {
    max-width: 100%;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }

  hr {
    background-color: var(--text);
  }
}
</style>
