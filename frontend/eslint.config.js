import eslint from '@eslint/js';
import tseslint from 'typescript-eslint'; 

export default tseslint.config(
  eslint.configs.recommended,
  tseslint.configs.recommended,
  {
    /*... */
    rules: {
      '@typescript-eslint/no-empty-object-type': 'warn',
      //     '@typescript-eslint/no-unsafe-argument': 'error',
      //     '@typescript-eslint/no-unsafe-assignment': 'error',
      //     '@typescript-eslint/no-unsafe-call': 'error',
      //     '@typescript-eslint/no-unsafe-member-access': 'error',
      //     '@typescript-eslint/no-unsafe-return': 'error',  
    }
  }, 
  // ...
);