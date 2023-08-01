<template>
  <div class="modal_el">
    <div class="modal_wrap" ref="modalWrap">
      <div class="modal_ttl">タスクの追加</div>
      <div class="add_task_el">
        <input
          class="input_contents input_task"
          v-model="form.task"
          type="text"
          name="task"
          placeholder="タスクを入力..."
          required
        />
        <div class="input_contents add_tag">
          タグ<button @click="addTag($event)" class="add_tag_button">
            {{ addTagButtonText }}</button
          ><br />
        </div>
        <ul class="checked_tags">
          <li v-for="tag in selectedTags" :key="tag">{{ tag }}</li>
        </ul>
        <div v-if="inputTagForm" class="input_contents input_tag">
          <div
            class="select_tag"
            v-if="selectTagList"
            :value="selectedTags"
            size="4"
            name="tag"
            multiple
          >
            <div class="all_tags">タグ一覧</div>
            <div
              v-for="option in addTagHistory"
              :value="option"
              :key="option"
              @click.stop="toggleAddTag(option)"
            >
              {{ option }}
              <span v-if="isSelected(option)">✔︎</span>
            </div>
          </div>
          <div class="create_tag_el">
            <input
              type="text"
              v-model="newTag"
              name="tag"
              placeholder="新しいタグを入力"
            />
            <button class="create_tag_btn" @click="createTag($event)">
              作成
            </button>
          </div>
          <p class="error_same_tag">{{ sameTagError }}</p>
        </div>
        <div class="input_contents btn_group">
          <button class="cancel_btn" @click="closeModal">キャンセル</button>
          <button class="add_task_btn" @click="handleSubmit">追加</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import {
  Vue,
  Prop,
  Watch,
  Emit,
  Ref,
  Component,
} from "nuxt-property-decorator";

@Component
export default class Modal extends Vue {
  @Prop({ type: String, required: true }) status!: string;
  @Prop({ type: Array, required: true }) tagHistory!: string[];
  @Prop({ type: Number, required: true }) index!: Number;
  @Prop({ type: Boolean, required: true }) showModal!: Boolean;

  //data
  form = {
    task: "",
    tag: [],
    status: this.status,
  };

  inputTagForm = false;
  selectTagList = false;
  newTag = ""; // Data property for restore new tag
  selectedTags: string[] = [];
  addTagButtonText = "+";
  sameTagError = "";

  //computed
  get addTagHistory(): string[] {
    return this.tagHistory.filter((tag) => tag !== "未選択");
  }

  @Watch("tagHistory", { immediate: true })
  onChangeTagHistory(newVal: string[]): void {
    this.selectTagList = newVal.length > 0;
  }

  addTag($event: any) {
    $event.preventDefault();
    this.addTagButtonText = this.addTagButtonText === "+" ? "−" : "+"; // Switch button text + and -
    this.inputTagForm = !this.inputTagForm;
    // Switch display and hiden inputTagform element(including select box and input tag to create newTag)
    if (this.sameTagError) {
      this.sameTagError = "";
    }
  }
  createTag($event: any): void {
    $event.preventDefault();
    if (!this.tagHistory.includes(this.newTag)) {
      this.$emit("addNewTag", this.newTag)
      this.newTag = ""; // Reset the value of newTag input field
      this.sameTagError = "";
      this.selectTagList = true;
    } else {
      this.sameTagError = "既に登録されているタグです";
      this.newTag = "";
    }
  }

  toggleAddTag(tag: string) {
    const index = this.selectedTags.indexOf(tag);
    if (index !== -1) {
      this.selectedTags.splice(index, 1); // Remove checked tags from selectTags Array
    } else {
      this.selectedTags.push(tag); // Add checked tags to selectTags Array
    }
  }

  isSelected(tag: string) {
    if (this.inputTagForm === true) {
      return this.selectedTags.includes(tag);
    }
  }

  handleSubmit() {
    const formData = {
      name: this.form.task,
      tags: this.selectedTags,
    };
    if (formData.name !== "") {
      this.$emit("addTask", formData, this.index);
      this.$emit("closeModal");
    }
  }

  closeModal() {
    this.$emit("closeModal");
  }

  handleOutsideClick(event: any) {
    const modalWrap = this.$refs.modalWrap as HTMLElement;
    const isOutsideModal = !modalWrap.contains(event.target); // モーダル内の要素かどうかを判定
    if (this.showModal === true && isOutsideModal === true) {
      this.closeModal();
    }
  }

  mounted() {
    document.addEventListener("click", this.handleOutsideClick);
  }

  beforeDestroy() {
    document.removeEventListener("click", this.handleOutsideClick);
  }
}
</script>

<style scoped>
.modal_el {
  position: fixed;
  top: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 2;
}
.modal_wrap {
  max-width: 450px;
  max-height: 522px;
  min-height: 250px;
  min-width: 330px;
  padding: 40px 60px;
  background-color: white;
  position: relative;
}
.add_task_el {
  display: flex;
  flex-direction: column;
}

.modal_ttl {
  text-align: center;
  font-size: 20px;
  margin-bottom: 20px;
}
.input_task {
  width: 100%;
  height: 51px;
  font-size: 20px;
}

.add_tag {
  font-size: 14px;
  margin-top: 10px;
}

.checked_tags {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
  flex-wrap: wrap;
}
li {
  background-color: #a1af2f;
  color: white;
  padding: 3px 10px;
  margin: 5px 5px 5px 0;
  white-space: nowrap;
  border-radius: 10px;
}
p,
ul {
  font-size: 14px;
}

.input_tag {
  height: 70%;
  margin-bottom: 40px;
}
.all_tags {
  text-align: center;
  color: white;
  background-color: black;
  position: sticky;
  top: 0;
}
.select_tag {
  width: 170px;
  height: 170px;
  border: 1px solid black;
  overflow: auto;
}

.error_same_tag {
  color: red;
}

.add_tag_button {
  width: 25px;
  text-align: center;
  border: none;
  border-radius: 35%;
  outline: none;
  background: transparent;
  background-color: #d9d9d9;
  margin-left: 10px;
}
.add_tag_button:active {
  background-color: rgba(0, 0, 0, 0.5);
}
.btn_group {
  font-size: 14px;
  display: flex;
  gap: 5px;
  position: absolute;
  bottom: 60px;
}
.create_tag_el {
  margin-top: 5px;
  display: flex;
  gap: 5px;
  position: relative;
}
.create_tag_btn,
.add_task_btn {
  color: white;
  background-color: black;
}
</style>

