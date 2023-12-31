<template>
  <div class="container">
    <div class="row">
      <div class="tag_filter">
        <TagFilter
          :tagHistory="tagHistory"
          @filterTags="filterTags"
          @addOptionUnselected="addOptionUnselected"
        />
      </div>
      <div class="task_list_row">
        <div class="col" v-for="(tasks, index) in tasks_group" :key="index">
          <div class="status_el">
            <div
              class="task_status_mark"
              :style="{ background: taskStatusColors[index] }"
            >
              {{ tasks_status_mark[index] }}
            </div>
            <h2 v-if="isFiltering">
              {{ statusList[index] }}
              <span class="task_count">{{ filteredTasks[index].length }}</span>
            </h2>
            <h2 v-else>
              {{ statusList[index]
              }}<span class="task_count">{{ tasks.length }}</span>
            </h2>
          </div>

          <TaskList
            :status="statusList[index]"
            :tasks="tasks_group[index]"
            :filteredTasks="filteredTasks[index]"
            :isFiltering="isFiltering"
            :index="index"
            @updateTasks="updateTasks(index, $event)"
            @openModal="openModal(statusList[index], index)"
          />
        </div>
      </div>
    </div>
    <div class="modal">
      <Modal
        @closeModal="closeModal"
        v-if="showModal"
        :status="selectedStatus"
        :index="taskIndex"
        :tagHistory="tagHistory"
        :showModal="true"
        @addTask="addTask"
        @addNewTag="addNewTag"
      ></Modal>
    </div>
  </div>
</template>

<script lang="ts">
import {
  Component,
  Vue,
  Watch,
} from "nuxt-property-decorator";
import TagFilter from "./TagFilter.vue";
import TaskList from "./TaskList.vue";
import Modal from "./Modal.vue";

@Component({
  components: {
    TagFilter,
    TaskList,
    Modal,
  },
})
export default class TodoApp extends Vue {
  status: string = "";
  taskIndex: number = -1;
  tasks_status_mark: string[] = [];
  statusList = ["未対応", "処理中", "レビュー中", "完了"];
  showModal: boolean = false;
  tasks_group = [
    // dummy data for demonstrating draggable tags
    [
      { name: "test", tags: ["tag1", "tag2"] },
      { name: "test2", tags: ["tag3", "tag4"] },
    ],
    [
      { name: "test", tags: ["tag1", "tag2"] },
      { name: "test2", tags: ["tag3", "tag4"] },
    ],
    [
      { name: "test", tags: ["tag1", "tag2"] },
      { name: "test2", tags: ["tag3", "tag4"] },
    ],
    [
      { name: "test", tags: ["tag1", "tag2"] },
      { name: "test2", tags: ["tag3", "tag4"] },
    ],
  ];
  isFiltering = false;
  filteredTags: string[] = [];
  filteredTasks: { name: string; tags: string[] }[][] = this.tasks_group.map(
    (tasks) => tasks.slice()
  );
  selectedStatus = "";
  tagHistory = ["tag1", "tag2", "tag3", "tag4"];
  taskStatusColors = ["#ED8077", "#4487C5", "#5EB5A6", "#A1AF2F"];

  //computed
  get roundColor(): string {
    // 現在のindexの値を取得するため、.valueを使う
    const index = Number(this.status);
    return this.taskStatusColors[index];
  }

  @Watch("filteredTasks", { immediate: false, deep: true })
  handler(newFilteredTasks: { name: string; tags: string[] }[][]) {
    console.log(newFilteredTasks);
  }

  openModal(status: string, index: number) {
    this.selectedStatus = status;
    this.taskIndex = index;
    this.showModal = true;
  }

  filterTags(selectedTags: string[]) {
    if (selectedTags.length === 0 || selectedTags.includes("未選択")) {
      this.isFiltering = false;
    } else {
      this.isFiltering = true;
      this.filteredTasks = this.tasks_group.map((tasks) =>
        tasks.filter((task) => {
          return selectedTags.every((tag) => task.tags.includes(tag));
        })
      );
      // this.$set(this, "filteredTasks", this.filteredTasks);
    }
    this.filteredTags = selectedTags;
    if (this.showModal) {
      const index = this.taskIndex;
      const newTask = {
        name: "",
        tags: selectedTags, // tagsプロパティには空の配列を代入
      };
      this.addTask(newTask, index);
      this.closeModal();
    }
  }
  // Add option "未選択" to {{ option }} in TagFilter component
  addOptionUnselected(option: string) {
    this.tagHistory.unshift(option);
  }

  //Register new tag to tagHistory
  addNewTag(tag: string) {
    this.tagHistory.push(tag);
  }

  // There is $emit element in handleSubmit function of Modal component
  addTask(task: { name: string; tags: string[] }, index: number): void {
    this.tasks_group[index].unshift(task);
    if (this.filteredTags.every((tag) => task.tags.includes(tag))) {
      this.filteredTasks[index].unshift(task);
    }
  }

  closeModal() {
    this.showModal = false;
    this.selectedStatus = "";
  }

  updateTasks(index: number, newList: { name: string; tags: string[] }[]) {
    this.filteredTasks[index] = newList;
  }
}
</script>

<style scoped>
.container {
  align-items: center;
  height: 100%;
  width: 100%;
}
.row {
  width: 80%;
  height: 100%;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.tag_filter {
  width: 150px;
  height: 100px;
  gap: 0;
  z-index: 1;
}

.task_list_row {
  position: absolute;
  max-width: 90%;
  display: flex;
  margin: 100px 0;
  gap: 25px;
  z-index: 0;
  overflow: hidden;
  flex-grow: 1;
}

.col {
  flex: 0 0 250px;
  width: 250px;
  height: 574px;
}
.status_el {
  display: flex;
  align-items: center;
  gap: 10px;
}

.task_status_mark {
  display: block;
  width: 15px;
  height: 15px;
  border-radius: 50%;
}
h2 {
  font-size: 16px;
}

.task_count {
  border-radius: 35%;
  width: 45px;
  margin-left: 10px;
  padding: 5px 10px;
  font-size: 14px;
  text-align: center;
  height: 25px;
  background-color: #d9d9d9;
}
.modal {
  width: 100vw;
}
</style>


