<template>
  <el-pagination
    background
    :layout="layout"
    :page-sizes="pageSizes"
    :total="total"
    :current-page="currentPage"
    @size-change="handelSizeChange"
    @next-click="handelPageChange"
    @prev-click="handelPageChange"
    @current-change="handelPageChange"
  ></el-pagination>
</template>

<script>
export default {
  props: {
    //接收从父组件
    pageSizes: {
      //分页器的每页显示XX条，非必传，若不传就显示默认条数
      type: Array, //Array/Object必须使用工厂函数来返回一个值
      required: false,
      default() {
        return [10, 50, 100, 150, 200];
      }
    },
    layout: {
      //组件布局，非必传，字符串格式，用逗号分隔，若不传就显示默认组件布局
      type: String,
      required: false,
      default: "total, sizes, prev, pager, next, jumper"
    },
    total: {
      //总条数，非必传，数字格式，若不传总条数为默认值
      type: Number,
      required: false,
      default: 0
    },
    currentPage: {
      //当前页，非必传，数字格式，若不传当前页为默认值
      type: Number,
      required: false,
      default: 1
    }
  },
  data() {
    return {};
  },
  methods: {
    // 每页条数
    handelSizeChange(val) {
      console.log("handelSizeChange", val);
      //触发父组件事件
      this.$emit("size-change", val); //父组件事件名@size-change
    },

    // 翻页
    handelPageChange(page) {
      console.log("handelPageChange", page);
      //触发父组件事件
      this.$emit("page-change", page); //父组件事件名@page-change
    }
  },

  mounted() {},
  created() {
    console.log("i am pagination component");
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
</style>

