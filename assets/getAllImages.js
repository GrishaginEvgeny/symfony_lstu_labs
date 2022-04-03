function importAll(r) {
    let images = {};
    r.keys().map((item, index) => { images[item.replace('./avatars', '')] = r(item); });
    return images;
}

const users_avatars = importAll(require.context('./uploads/avatars/users', false, /\.(png|jpe?g|svg)$/));
const blogs_avatars = importAll(require.context('./uploads/avatars/blogs', false, /\.(png|jpe?g|svg)$/));
const postpictures = importAll(require.context('./uploads/posts', false, /\.(png|jpe?g|svg)$/));
const comments = importAll(require.context('./uploads/comments', false, /\.(png|jpe?g|svg)$/));