const { glob } = require("glob");
const path = require("path");

module.exports = {
    mode: "development",

    entry: glob.sync("./src/ts/*.ts").reduce((entries, el) => {
        entries[path.parse(el).name] = el;

        return entries;
    }, {}),

    output: {
        path: path.join(__dirname, "storage/app/dist/"),
        publicPath: "/dist/",
        filename: "[name].bundle.js",
        clean: true,
    },

    optimization: {
        splitChunks: {
            chunks: "all",
        },
    },

    resolve: {
        extensions: [".ts", ".tsx", ".js"],
    },

    module: {
        rules: [
            {
                test: /\.(css|sass|scss)$/,
                use: [
                    "style-loader",
                    "css-loader",
                    "postcss-loader",
                    {
                        loader: "sass-loader",
                        options: {
                            sassOptions: { includePaths: ["./node_modules"] },
                        },
                    },
                ],
            },
            {
                test: /\.tsx?$/,
                use: "ts-loader",
                exclude: /node_modules/,
            },
        ],
    },
};
