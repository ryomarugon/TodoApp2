<template>
  <div class="task_list" v-if="isFiltering">
    <div class="add_task_btn">
      <button @click.stop="openModal(status, index)">
        +<span class="text-success">課題の追加</span>
      </button>
    </div>
    <div class="card_columns">
      <div v-for="task in filteredTasks" :key="task.id">
        <div class="task_item">
          <div>
            <p class="task_name">{{ task.name }}</p>
            <ul class="tag_list">
              <li v-for="tagItem in task.tags" :key="tagItem">
                {{ tagItem }}
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div v-else class="task_list">
    <div class="add_task_btn">
      <button @click.stop="openModal(status, index)">
        +<span class="text-success">課題の追加</span>
      </button>
    </div>
    <div class="card_columns">
      <draggable
        :list="tasks"
        @updateTasks="updateTasks"
        item-key="no"
        :animation="700"
        group="taskGroup"
        class="tasks-box"
      >
        <div class="task_item" v-for="element in tasks" :key="element.id">
          <!-- <div> -->
          <p class="task_name">{{ element.name }}</p>
          <ul class="tag_list">
            <li v-for="tagItem in element.tags" :key="tagItem">
              {{ tagItem }}
            </li>
          </ul>
          <!-- </div> -->
        </div>
      </draggable>
    </div>
  </div>
</template>

<script lang="ts">
import draggable from "vuedraggable";
import { Vue, Prop, Emit, Component } from "nuxt-property-decorator";

@Component({
  components: {
    draggable,
  },
})
export default class TaskList extends Vue {
  @Prop({ type: String, required: true }) status!: string;
  @Prop({ type: Array, required: true }) tasks!: string[];
  @Prop({ type: Array, required: true }) filteredTasks!: string[];
  @Prop({ type: Boolean, required: true }) isFiltering!: boolean;
  @Prop({ type: Number, required: true }) index!: number;

  //emit
  openModal(status: string, index: number) {
    this.$emit("openModal", status, index);
  }
  updateTasks(newList: any) {
    this.$emit("updateTasks", newList.slice());
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

