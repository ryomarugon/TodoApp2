<template>
  <div class="container">
    <div class="row">
      <p>タグ</p>
      <div class="filtering_box">
        <div
          class="selected_tags"
          @click="showFilterTags($event)"
          ref="selectedTags"
        >
          <p class="">▼</p>
        </div>
        <div
          v-if="showFilteringEl"
          class="show_filtering_el"
          :style="{ width: getWidth }"
        >
          <div class="filtering_tags">
            <input
              type="text"
              class="searching_tag"
              placeholder="タグを検索…"
              @input="handleTagSearch"
            />
            <div
              class="tag_list"
              v-for="option in tagHistory"
              :value="option"
              :key="option"
              @click="toggleFilterTag(option)"
              v-show="isTagVisible(option)"
            >
              {{ option }}
              <span v-if="isSelected(option)">✔︎</span>
            </div>
          </div>
          <div class="filtering_btn" @click="filteringTags()">絞り込む</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, defineProps, defineEmits, computed } from "vue";

//props
const props = defineProps({
  tagHistory: {
    type: Array,
    required: true,
  },
  tasks: {
    type: Array,
    required: true,
  },
});

//emit
const emit = defineEmits(["filterTags"]);

//data()
const showFilteringEl = ref(false);
const searchText = ref("");
const filteredTags = ref([]);

//ref
const selectedTags = ref(null);

//computed
const getWidth = computed(() => {
  const selectedTagsWidth = selectedTags.value.clientWidth;
  return selectedTagsWidth ? `${selectedTagsWidth}px` : "100%";
});

//method
function showFilterTags($event) {
  $event.preventDefault();
  if (!props.tagHistory.includes("未選択")) {
    props.tagHistory.unshift("未選択");
  }
  showFilteringEl.value = !showFilteringEl.value;
  if (!showFilteringEl.value) {
    searchText.value = "";
  }
}
function handleTagSearch(event) {
  searchText.value = event.target.value;
}
function isTagVisible(tag) {
  if (searchText.value == "") {
    return true; //if the text typed in searching filed was none, display all option
  } else {
    if (tag.includes(searchText.value)) {
      return true; //if the text typed in searching filed was included in tagHistory, display the tags
    }
  }
}
function toggleFilterTag(tag) {
  //If selected tag is "未選択（unselected）" is clicked
  if (tag === "未選択") {
    if (!filteredTags.value.includes(tag)) {
      filteredTags.value = [tag]; //If "未選択（unselected）" was not included in selected tags and it was clicked, the content of tagHistory is only "未選択（unselected）"
    } else {
      filteredTags.value = []; //If "未選択（unselected）" was included in selected tags and it was clicked, the content of tagHistory is none"
    }
  } else {
    //If other than "未選択（unselected）" is clicked
    if (filteredTags.value.includes(tag)) {
      filteredTags.value.splice(filteredTags.value.indexOf(tag), 1); //If the option was included in selected tags, it is cleared
    } else {
      if (filteredTags.value.includes("未選択")) {
        filteredTags.value.splice(filteredTags.value.indexOf("未選択"), 1); //If the option wasn't included and "未選択（unselected）" was included in selected tags, "未選択（unselected）" is cleared
      }
      filteredTags.value.push(tag);
    }
  }
}
function isSelected(tag) {
  if (showFilteringEl.value === true) {
    const isSelected = filteredTags.value.includes(tag);
    return isSelected;
  }
}
function filteringTags() {
  emit("filterTags", filteredTags.value);
  showFilteringEl.value = !showFilteringEl.value;
}
</script>
<style scoped>
.container,
.row {
  width: 100%;
}
.container {
  height: 150px;
}

.filtering_box {
  width: 100%;
  height: 30px;
  cursor: pointer;
}

.selected_tags {
  background-color: white;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  height: 15px;
  width: 100%;
  padding: 5px;
  border: 1px solid black;
}
.show_filtering_el {
  background-color: white;
  display: flex;
  flex-direction: column;
  height: 135px;
  width: 100%;
  border: 1px solid black;
  overflow: auto;
}
.filtering_tags {
  margin: 5px 5px 25px 5px;
  height: 100%;
}

.searching_tag {
  width: 90%;
  height: 15px;
  padding: 5px;
  border: 1px solid black;
  display: flex;
  justify-content: center;
  margin-bottom: 5px;
}

.filtering_btn {
  color: white;
  background-color: black;
  text-align: center;
  position: sticky;
  bottom: 0;
}
</style>

