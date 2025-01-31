FROM node:latest
 
# Set working directory
WORKDIR /app

# this will allow us to run vite and other tools directly
ENV PATH /app/node_modules/.bin:$PATH
  
# Install dependencies
COPY package*.json ./
 
# Install dependencies
RUN npm install

# Copy project files
COPY . . 
 
# Expose port and start the application [dev, prod, hmr]
EXPOSE 3000
EXPOSE 24678

CMD ["sh", "-c", "npm run dev --host"]