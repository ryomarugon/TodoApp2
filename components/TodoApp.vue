<template>
  <div class="container">
    <div class="row">
      <div class="tag_filter">
        <TagFilter
          :tagHistory="tagHistory"
          :tasks="tasks_group"
          @filterTags="filterTags"
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
      ></Modal>
    </div>
  </div>
</template>

<script setup>
import TagFilter from "./TagFilter.vue";
import TaskList from "./TaskList.vue";
import Modal from "./Modal.vue";
import {ref, computed } from "vue";

const status = ref("");
const taskIndex = ref("");
const tasks_status_mark = ref([]);
const statusList = ["未対応", "処理中", "レビュー中", "完了"];
const showModal = ref(false);
const tasks_group = ref([
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
]);
const isFiltering = ref(false);
const filteredTags = ref([]);
const filteredTasks = ref([]);
const selectedStatus = ref("");
const tagHistory = ref(["tag1", "tag2", "tag3", "tag4"]);
const taskStatusColors = ref(["#ED8077", "#4487C5", "#5EB5A6", "#A1AF2F"]);

const roundColor = computed(() => {
  // 現在のindexの値を取得するため、.valueを使う
  const index = status.value;
  return taskStatusColors[index];
});
//Set filteredTasks when it was reset
filteredTasks.value = tasks_group.value.map((tasks) => tasks.slice());
console.log(filteredTasks.value);

function openModal(status, index) {
  taskIndex.value = index;
  selectedStatus.value = status;
  showModal.value = true;
  event.stopPropagation();
}

function filterTags(selectedTags) {
  console.log("selected tags: ", selectedTags);
  if (selectedTags.length === 0 || selectedTags.includes("未選択")) {
    isFiltering.value = false;
    console.log(tasks_group);
    console.log(filteredTags);
    console.log(isFiltering);
  } else {
    isFiltering.value = true;
    console.log(isFiltering);
    filteredTasks.value = tasks_group.value.map((tasks) =>
      tasks.filter((task) => {
        return selectedTags.every((tag) => task.tags.includes(tag));
      })
    );
    console.log(filteredTasks);
  }
  filteredTags.value = selectedTags;
  if (showModal.value) {
    const index = taskIndex;
    addTask(selectedTags.value, index);
    closeModal();
  }
}

// There is $emit element in handleSubmit function of Modal component
function addTask(task, index) {
  console.log(tasks_group[index]);
  tasks_group.value[index].unshift(task);
  console.log(filteredTags);
  if (filteredTags.value.every((tag) => task.tags.includes(tag))) {
    filteredTasks.value[index].unshift(task);
  }
}

function closeModal() {
  showModal.value = false;
  selectedStatus.value = "";
}
function updateTasks(index, newList) {
  tasks_group[index] = newList;
  // this.$set(this.tasks_group, index, newList);
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


