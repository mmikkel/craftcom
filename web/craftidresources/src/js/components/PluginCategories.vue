<template>
	<div>
		<div class="form-group">
			<label id="categories">Categories</label>
			<div class="instructions"><p>Pick up to {{maxCategories}} categories. ({{ pluginDraft.categoryIds.length }}/{{maxCategories}} selected)</p></div>

			<draggable v-model="pluginDraft.categoryIds">
				<div class="alert alert-secondary float-left clearboth" v-for="category in selectedCategories">
					<div class="d-flex">
						<div>{{category.title}}</div>
						<div class="ml-3 mt-1">
							<a class="" href="#" @click.prevent="unselectCategory(category.id)"><i class="fa fa-remove text-danger"></i></a>
						</div>
					</div>
				</div>
			</draggable>

			<div class="clearfix"></div>

			<div>
				<div class="d-inline-block" v-for="category in availableCategories">
					<a class="btn btn-outline-secondary mb-2" :class="{disabled: pluginDraft.categoryIds.length >= maxCategories }" href="#" @click.prevent="selectCategory(category.id)"><i class="fa fa-plus"></i> {{category.title}}</a>&nbsp;
				</div>
			</div>
		</div>
	</div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import draggable from 'vuedraggable'

    export default {

        components: {
            draggable
        },


        props: ['pluginDraft'],

		data() {
          	return {
          	    maxCategories: 3,
			}
		},

		computed: {

            ...mapGetters({
                categories: 'categories',
            }),

            selectedCategories() {
                let categories = [];

                this.pluginDraft.categoryIds.forEach(categoryId => {
                    const category = this.categories.find(c => c.id == categoryId);
                 	categories.push(category);
				});

                return categories;
            },

            availableCategories() {
                return this.categories.filter(category => {
                    return !this.pluginDraft.categoryIds.find(categoryId => categoryId == category.id);
                })
            },

            categoryOptions() {
                let options = [];

                this.categories.forEach(category => {
                    let checked = this.pluginDraft.categoryIds.find(categoryId => categoryId == category.id);

                    let option = {
                        label: category.title,
                        value: category.id,
                        checked: checked,
                    };

                    options.push(option);
                });

                return options;
            }

		},

		methods: {

            selectCategory(categoryId) {
                if(this.pluginDraft.categoryIds.length < this.maxCategories) {
					const exists = this.pluginDraft.categoryIds.find(catId => catId == categoryId);

					if(!exists) {
						this.pluginDraft.categoryIds.push(categoryId);
					}
                }
            },

            unselectCategory(categoryId) {
                const i = this.pluginDraft.categoryIds.indexOf(categoryId);

                if(i !== -1) {
                    this.pluginDraft.categoryIds.splice(i, 1);
                }
            },

		}
    }
</script>