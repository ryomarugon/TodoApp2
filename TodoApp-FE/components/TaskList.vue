<template>
  <div class="task_list" v-if="isFiltering">
    <div class="add_task_btn">
      <button @click.stop="openModal(status, index)">
        +
        <span class="text-success">課題の追加</span>
      </button>
    </div>
    <div class="card_columns">
      <div v-for="task in filteredTasks" :key="task.id">
        <div class="task_item">
          <div>
            <p class="task_name">{{ task.name }}</p>
            <ul class="tag_list">
              <li v-for="tagItem in task.tags" :key="tagItem">{{ tagItem }}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="task_list">
    <div class="add_task_btn">
      <button @click.stop="openModal(status, index)">
        +
        <span class="text-success">課題の追加</span>
      </button>
    </div>

    <!-- updateTasksが何の意図かわかりません -->
    {{ taskPositions }}
    <div class="card_columns">
      <draggable
        :list="tasks"
        item-key="item=>item.order"
        :animation="700"
        group="taskGroup"
        class="tasks-box"
      >
        <div class="task_item" v-for="element in tasks" :key="element.id">
          <!-- <div> -->
          <!-- {{ element }} -->
          <p class="task_name">{{ element.name }}</p>
          <ul class="tag_list">
            <li v-for="tagItem in element.tags" :key="tagItem">{{ tagItem }}</li>
          </ul>
          <!-- </div> -->
        </div>
      </draggable>
    </div>
  </div>
</template>

<script lang="ts">
import draggable from "vuedraggable";
import { Vue, Prop, Component, Watch } from "nuxt-property-decorator";

interface Task {
  name: string;
  tags: string[];
  status: number;
  order: number;
}

@Component({
  components: {
    draggable
  }
})
export default class TaskList extends Vue {
  @Prop({ required: true }) status!: number;
  @Prop({ required: true }) tasks!: Task[];
  @Prop({ required: true }) filteredTasks!: string[];
  @Prop({ required: true }) isFiltering!: boolean;
  @Prop({ required: true }) index!: number;
  //emit
  openModal(status: number, index: number) {
    this.$emit("openModal", status, index);
  }

  get taskPositions() {
    return this.tasks.map((task: any, index: number) => {
      task.order = index;
      task.status = this.status;
      return task;
    });
  }

  @Watch("tasks")
  updatedDatabase() {
    console.log("UPDATE DB WITH THIS DATA: ", this.taskPositions);
    this.updateTasks();
  }

  //何のため？？
  async updateTasks() {
    try {
      // Send the new order and updated tasks to the server for saving
      const reorderedTasks = await this.$axios.$post(
        "http://127.0.0.1:8000/api/updateTasksOrder",
        this.taskPositions
      );
      console.log(reorderedTasks);
    } catch (error) {
      console.error("Error updating tasks:", error);
    }
  }
}
</script>
<style scoped>
.task_list {
  display: flex;
  flex-direction: column;
  background-color: white;
  padding: 10px;
  gap: 15px;
  width: 230px;
  height: 554px;
  overflow: auto;
}
button {
  background: none;
  border: none;
}
.text-success {
  color: green;
}
.card_columns {
  height: 90%;
}
.tasks-box {
  height: 100%;
}
.task_item {
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  background-color: white;
  min-height: 75px;
  border: 1px solid #ccc;
  padding: 5px;
  margin-bottom: 10px;
  word-wrap: break-word;
}
.tag_list {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
  flex-wrap: wrap;
}
li {
  background-color: #a1af2f;
  color: white;
  margin-right: 5px;
  margin-top: 5px;
  white-space: nowrap;
  padding: 3px 10px;
  border-radius: 10px;
}
</style>

