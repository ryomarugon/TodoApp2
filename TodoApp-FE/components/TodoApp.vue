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
        <div class="col" v-for="(tasks, index) in fetchedTasksByStatus" :key="index">
          <div class="status_el">
            <div
              class="task_status_mark"
              :style="{ background: taskStatusColors[index] }"
            >{{ tasks_status_mark[index] }}</div>
            <h2>{{ statusList[index] }}</h2>
            <span v-if="isFiltering" class="task_count">
              <strong>{{ filteredTasks[index].length }}</strong>
            </span>
            <span v-else class="task_count">
              <strong>{{ fetchedTasksByStatus[index].length }}</strong>
            </span>
          </div>
          <!--indexの示す場所-->
          <!-- @updateTasksの意図と引数 -->
          <TaskList
            :status="statusList[index]"
            :tasks="fetchedTasksByStatus[index]"
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
        @addTag="addTag"
      ></Modal>
    </div>
  </div>
</template>

<script lang="ts">
import { Component, Vue, Watch } from "nuxt-property-decorator";
import TagFilter from "./TagFilter.vue";
import TaskList from "./TaskList.vue";
import Modal from "./Modal.vue";
import $axios from "@nuxtjs/axios";

@Component({
  components: {
    TagFilter,
    TaskList,
    Modal
  }
})
export default class TodoApp extends Vue {
  status: string = "";
  taskIndex: number = -1;
  tasks_status_mark: string[] = [];
  statusList = [0, 1, 2, 3];
  showModal: boolean = false;
  fetchedTasks = [];
  fetchedTasksByStatus = [];
  isFiltering = false;
  filteredTasks = [];
  selectedTags: string[] = [];
  selectedStatus: number = 0;
  tagHistory = [];
  taskStatusColors = ["#ED8077", "#4487C5", "#5EB5A6", "#A1AF2F"];

  async mounted() {
    try {
      const getAllTasks = await this.$axios.$get(
        "http://127.0.0.1:8000/api/tasks"
      );
      // name と tags のみを取り出して fetchedTasks に格納
      this.fetchedTasks = getAllTasks;
      for (let i = 0; i < 4; i++) {
        this.fetchedTasksByStatus[i] = this.fetchedTasks.filter(
          task => task.status === i
        );
        this.fetchedTasksByStatus = [...this.fetchedTasksByStatus];
      }
      console.log(this.fetchedTasksByStatus);
      let getAllTags = await this.$axios.$get("http://127.0.0.1:8000/api/tags");
      console.log(getAllTags);
    
      this.tagHistory = getAllTags.map(tag => tag.name);
      // console.log(this.tagHistory);
    } catch (error) {
      console.log("a");
    }
  }

  //computed
  get roundColor(): string {
    // 現在のindexの値を取得するため、.valueを使う
    const index = Number(this.status);
    return this.taskStatusColors[index];
  }

  // ここがわからない
  // @Watch("filteredTasks", { immediate: false, deep: true })
  // handler(newFilteredTasks: { name: string; tags: string[] }[][]) {
  //   console.log(newFilteredTasks);
  // }

  openModal(status: number, index: number) {
    this.selectedStatus = status;
    this.showModal = true;
  }

  filterTags(selectedTags: string[]) {
    if (selectedTags.length === 0 || selectedTags.includes("未選択")) {
      this.isFiltering = false;
    } else {
      this.isFiltering = true;
      this.selectedTags = selectedTags;
      // console.log(this.filteredTasks);
      for (let i = 0; i < 4; i++) {
        this.filteredTasks[i] = this.fetchedTasksByStatus[i].filter(
          fetchedTask => {
            return selectedTags.every(tag => fetchedTask.tags.includes(tag));
          }
        );
        this.filteredTasks = [...this.filteredTasks];
      }
    }
    // if (this.showModal) {
    //   const newTask = {
    //     name: "",
    //     tags: selectedTags,
    //     status: this.selectedStatus // tagsプロパティには空の配列を代入
    //   };
    //   console.log(newTask)
    //   this.addTask(newTask);
    //   this.closeModal();
    // }
  }
  // Add option "未選択" to {{ option }} in TagFilter component
  addOptionUnselected(option: string) {
    this.tagHistory.unshift(option);
  }

  //Register new tag to tagHistory
  async addTag(tag: {name : string}): Promise<void> {
    try {
      const postTag = await this.$axios.$post(
        "http://127.0.0.1:8000/api/tags",
        tag
      );
      console.log(postTag);
      const getAllTags = await this.$axios.$get(
        "http://127.0.0.1:8000/api/tags"
      );
      this.tagHistory = getAllTags.map(tag => tag.name);
      console.log(this.tagHistory);
    } catch (error) {
      // エラーハンドリング
      console.error(error);
    }
  }

  // There is $emit element in handleSubmit function of Modal component
  async addTask(task: {
    name: string;
    tags: string[];
    status: number;
  }): Promise<void> {
    try {
      let postTask = await this.$axios.$post(
        "http://127.0.0.1:8000/api/tasks",
        task
      );
      const getAllTasks = await this.$axios.$get(
        "http://127.0.0.1:8000/api/tasks"
      );
      this.fetchedTasks = getAllTasks;
      // console.log(this.fetchedTasksByStatus);
      for (let i = 0; i < 4; i++) {
        this.fetchedTasksByStatus[i] = this.fetchedTasks.filter(
          task => task.status === i
        );
        this.fetchedTasksByStatus = [...this.fetchedTasksByStatus];
      }

      // 更新された fetchedTasksByStatus を元に filteredTasks を再計算する
      if (this.isFiltering) {
        for (let i = 0; i < 4; i++) {
          this.filteredTasks[i] = this.fetchedTasksByStatus[i].filter(
            fetchedTask => {
              return this.selectedTags.every(tag =>
                fetchedTask.tags.includes(tag)
              );
            }
          );
        }
      }

      // console.log(this.fetchedTasksByStatus);
      console.log(this.filteredTasks);
      // console.log(this.fetchedTasksByStatus[0]);
      // console.log(this.fetchedTasksByStatus[1]);
      // console.log(this.fetchedTasksByStatus[2]);
      // console.log(this.fetchedTasksByStatus[3]);
    } catch (error) {
      // エラーハンドリング
      console.error(error);
    }
  }

  closeModal() {
    this.showModal = false;
  }

  //   updateTasks(index: number, newOrder: number[], updatedTasks: any[]) {
  //   // Update the order of tasks
  //   this.$set(this.fetchedTasksByStatus, index, updatedTasks);

  //   // Now, the fetchedTasksByStatus array for the corresponding status column is updated with the new order and the updated tasks
  // }
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
  margin-left: 10px;
  padding: 5px 10px;
  font-size: 14px;
  text-align: center;
  background-color: #d9d9d9;
}
.modal {
  width: 100vw;
}
</style>