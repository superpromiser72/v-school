<template>
    <!-- <Menu mode="horizontal" active-name="1">
        <MenuItem name="1">
            内容管理
        </MenuItem>
        <MenuItem name="2">
            内容管理
        </MenuItem>
        <MenuItem name="3">
            内容管理
        </MenuItem>
        <MenuItem name="4">
            内容管理
        </MenuItem>
        <Button type="success" class="addbtn" @click="addModal"><Icon type="md-add" /> 添加</Button>
    </Menu> -->
    <Button type="success" class="addbtn m-2" @click="addModal"><Icon type="md-add" /> 添加</Button>
</template>

<script>
export default {
    data(){
        return{
            menuItem:false,
        }
    },
    methods:{
        addModal(){
            this.menuItem = true;
            this.$emit('addModalemit',this.menuItem);
        }
    }
}
</script>

